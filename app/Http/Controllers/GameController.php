<?php

namespace App\Http\Controllers;

use App\Events\GameSessionUserEvent;
use App\Models\File;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\PortraitFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('game.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validating I've received the image
        $request->validate([
            'file' => 'required|image|max:2048',
            'rows' => 'required',
            'cols' => 'required'
        ]);

        //Image I want to work with
        $source = imagecreatefromjpeg( $request->file('file') );
//        return dd($request->file('file')->storeAs('public/images/','Prueba.jpg'));
//        $testImage = $request->file('file')->storeAs('public/images/','Prueba.jpg');
        $source_width = imagesx( $source );
        $source_height = imagesy( $source );

        //getting image ratio
        $ratiow = 854/ $source_width; //854max width
        $ratioh = 560 / $source_width; //560max height
        $ratio = min($ratioh, $ratiow);

        //new width and height
        $newW = floor($source_width*$ratiow);
        $newY = floor($source_height*$ratioh);

        //creating the new image
        $resizedImage = imageCreateTrueColor($newW, $newY);
        imagecopyresampled($resizedImage, $source, 0, 0, 0, 0, $newW, $newY, $source_width, $source_height);

        $game = Game::create([
            'rows' => $request->rows,
            'cols' => $request->cols,
            'code_invitation' => \Illuminate\Support\Str::random(40),
            'user_id' => Auth::user()->getAuthIdentifier(),
            'status_id' => 1,
        ]);


//        $this->createDirecrotory(Storage::path('images/'));
//        return dd(Storage::url(''), Storage::path(''));
        $fulImagesUrl = Storage::path('public/images/game_' . $game->id . '.jpg');
        imagejpeg($resizedImage,$fulImagesUrl);
        imagedestroy($resizedImage);
//        return dd(Storage::path('images/game_' . $game->id . '.jpg'), Storage::url('public/images/game_' . $game->id . '.jpg'));


        PortraitFile::create([
            'game_id' => $game->id,
            'url' => Storage::url('public/images/game_' . $game->id . '.jpg'),
        ]);

        GameSession::create([
            'game_id' => $game->id,
            'user_id' => $game->user_id,
            'joined_time' => now('America/La_Paz'),

        ]);

        //Width and Height of each piece of image
        $cant_cols = $newW / ($newW / $request->cols);
        $cant_rows = $newY / ($newY / $request->rows);

        $piecesWidth = floor($newW / $cant_cols);
        $piecesHeight = floor($newY / $cant_rows);

        //These "for" are to find coordinate where to split image
        for( $col = 0; $col < $request->cols; $col++)
        {
            for( $row = 0;  $row < $request->rows; $row++)
            {
                //Name to the splited images
                $filePath = Storage::path('public/images/img0'.$col.  '_0' .$row.'ofGame'.$game->id. '.jpg');

                //Creating the new Image
                $im = @imagecreatetruecolor( $piecesWidth, $piecesHeight);
                //Setting the new image content from source in the specified coordinates
                imagecopyresized( $im, $resizedImage, 0, 0,$col * $piecesWidth, $row * $piecesHeight, $piecesWidth, $piecesHeight, $piecesWidth, $piecesHeight );
                //Output the new Image(im = the image, filePath = the path of folder to save it)
                imagejpeg($im, $filePath);
                //free memory
                imagedestroy( $im );
                //Path to access from server
                $fileName = 'public/images/img0'.$col.  '_0' .$row.'ofGame'.$game->id. '.jpg';
                $url = Storage::url($fileName);
                File::create([
                    'url' => $url,
                    'x_index' => $col,
                    'y_index'=> $row,
                    'is_ful_image'=> false,
                    'game_id' => $game->id,
                    'width' => $piecesWidth,
                    'height' => $piecesHeight,
                ]);

            }
        }
//        return dd($game->id);
        return redirect()->route('startGame', $game->id);
    }

    public function start($game_id){

        $game = Game::where('id',$game_id)->get();
        $all_images =  File::where('game_id', $game_id )->get();
        $ful_image  = PortraitFile::where('game_id',$game_id)->get();
//        $ful_image = asset($ful_image[0]->url);
//        return dd($ful_image);

        $images = $all_images->filter(function($selected_image) {
            return $selected_image->is_ful_image != true;
        });
//        return dd(count($images));
        $images = $images->shuffle();

        /*$assets = collect([]);
        for($i =0;  $i < count($images); $i++){
            $assets = $assets->push(asset($images[$i]->url));
        }*/

//        return dd($images,$ful_image);
        broadcast(new GameSessionUserEvent($game[0]))->toOthers();
        return view('index_images', compact('images', 'game', 'ful_image'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $games = Game::where('user_id', $user_id)->with(['portrait','status'])->get();

        return view('game.show', compact('games'));
    }

    public function joinToGame(Request $request){
        $request->validate([
           'code_invitation' => 'required'
        ]);
        $game = Game::where('code_invitation', $request->code_invitation)->with(['session'])->get();
        //Si no ha particiado antes en ese juego, creo una nueva sesion
        if( count($game) > 0){ //Verifico si el juego existe

            //Si existe, entonces verifico si el juegador no ha jugado antes ese puzzle
            $gameSession = $game[0]->session;

            for($i = 0; $i< count($gameSession); $i++){
                if($gameSession[$i]->user_id == Auth::user()->id){
                    $passedScore = $gameSession[$i]->score;
                    $passedMovements = $gameSession[$i]->movements;
                    broadcast(new GameSessionUserEvent($game[0]));
                    return redirect()->route('startGame',[$game[0]->id])->with([
                        ['passedScore',$passedScore],
                        ['passedMovements'=> $passedMovements]
                    ]);
                }
            }
            //Si no, entonces creo una nueva session
            GameSession::create([
                'game_id' => $game[0]->id,
                'score' => 0,
                'movements' => 0,
                'user_id' => Auth::user()->id,
                'joined_time' => Carbon::now('America/La_Paz'),
            ]);
            broadcast(new GameSessionUserEvent($game[0]));
            return redirect()->route('startGame',[$game[0]->id]);
        }else{ //Pero, si el juego no existe, entonces debo mostrar que el juego no existe
            $message = "Vaya, al parecer este puzzle no existe! :(";
        }
        return redirect()->route('')->with('message', $message);

    }

    public function setWinner(Request $request){
        $request->validate([
            'game_id' => 'required',
        ]);

        $game = Game::findOrFail($request->game_id);

        $game->winner = Auth::user()->name;
        $game->status_id = 2;
        $game->save();
        return response(json_encode($game->winner),200);
    }

    public function createDirecrotory($path)
    {
//        return dd($path);
        if(!\Illuminate\Support\Facades\File::isDirectory($path)){
            return (\Illuminate\Support\Facades\File::makeDirectory($path, 0755, true, true));
        }

    }
}

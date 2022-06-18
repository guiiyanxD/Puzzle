<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Game;
use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
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

//        $filePath = Storage::path('public\images\img0'.$col.  '_0' .$row.'ofGame'.$game->id. '.jpg');

//        $fulImageUrl = $request->file('file')->storeAs('public/images/','game_' . $game->id . '.jpg');
        $fulImagesUrl = Storage::path('public/images/game_' . $game->id . '.jpg');
        imagejpeg($resizedImage,$fulImagesUrl);
        imagedestroy($resizedImage);

        File::create([
            'game_id' => $game->id,
            'is_ful_image' => true,
            'width' => $newW,
            'height' => $newY,
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
                $filePath = Storage::path('public\images\img0'.$col.  '_0' .$row.'ofGame'.$game->id. '.jpg');
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

        $ful_image  = $all_images->firstOrFail();
//        return dd($ful_image);

        $images = $all_images->filter(function($selected_image) {
            return $selected_image->is_ful_image != true;
        });
//        return dd($images);
        $images = $images->shuffle();

        return view('index_images', compact('images', 'game', 'ful_image'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

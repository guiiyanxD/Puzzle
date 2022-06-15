<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Game;
use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use Psy\Util\Str;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images =  File::all();
        $game = Game::where('id',13)->get();
        return dd($game);
//        return view('index_images', compact('images','game'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * @param $image
     * @return false|\GdImage|resource
     * It only will work with jpeg, png and gif format of images and will return an image
     */
    public function createImageFromMime($image){
        $new_image = getimagesize($image);
        if($new_image['mime'] == 'image/gif')
            return imagecreatefromgif($image);
        elseif($new_image['mime'] == 'image/png')
            return imagecreatefrompng($image);
        else
            return imagecreatefromjpeg($image);
    }

    public function storeImage(Request $request){
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
        $newW = $source_width*$ratiow;
        $newY = $source_height*$ratio;

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

        $piecesWidth = $newW / $cant_cols;
        $piecesHeight = $newY / $cant_rows;

        //These "for" are to find coordinate where to split image
        for( $col = 0; $col < $cant_cols; $col++)
        {
            for( $row = 0;  $row < $cant_rows; $row++)
            {
                //Name to the splited images
                $filePath = Storage::path('public\images\img0'.$col.  '_0' .$row.'ofGame'.$game->id. '.jpg');
                //Creating the new Image
                $im = @imagecreatetruecolor( $piecesWidth, $piecesHeight);
                //Setting the new image content from source in the specified coordinates
                imagecopyresized( $im, $source, 0, 0,$col * $piecesWidth, $row * $piecesHeight, $piecesWidth, $piecesHeight, $piecesWidth, $piecesHeight );
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
//        return dd($counter);
        return redirect()->route('loadImage');
    }



    public function asRequest(Request $request){
        return $request->store('public/images');
    }

    public function indexImages(){
        $all_images =  File::all();
        $ful_image = $all_images[0];

        $images = $all_images->filter(function($selected_image){
            return $selected_image->is_ful_image != true;
        });

//        return dd(count($images));
        $images = $images->shuffle();
//        return dd($game);
        $game = Game::where('id',5)->get();

        return view('index_images', compact('images', 'game', 'ful_image'));
    }

    public function splitImages(){

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;

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
        return view('index_images', compact('images'));
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

    public function storeImage(Request $request){
        //validating I've received the image
        $request->validate([
            'file' => 'required|image|max:2048',
            'rows' => 'required',
            'cols' => 'required'
        ]);


        //Dimensions of each image protion
//        $width = $request->cols / $request->file->size;
//        $height = 100;

        //Image I want to work with
        $source = @imagecreatefromjpeg( $request->file('file') );
        $source_width = imagesx( $source );
        $source_height = imagesy( $source );

        $cant_cols = $source_width / ($source_width / $request->cols);
        $cant_rows = $source_height / ($source_height / $request->rows);

        $width = $source_width / $cant_cols;
        $height = $source_height / $cant_rows;

//        return dd($source_width, $cant_cols, $width , $source_height, $cant_rows, $height);

        $counter = 0;

        //These "for" are to find coordinate where to split image
        for( $col = 0; $col < $cant_cols; $col++)
        {
            for( $row = 0;  $row < $cant_rows; $row++)
            {
//                $counter =+ 1;
                //Name to the splited images
                $filePath = Storage::path('public\images\img0'.$col.  '_0' .$row.'.jpg');

                //Creating the new Image
                $im = @imagecreatetruecolor( $width, $height);

                //Setting the new image content from source in the specified coordinates
                imagecopyresized( $im, $source, 0, 0,
                    $col * $width, $row * $height, $width, $height,
                    $width, $height );
                //Output the new Image(im = the image, filePath = the path of folder to save it)
                imagejpeg($im, $filePath);

                //free memory
                imagedestroy( $im );


                //Path to access from server
                $fileName = 'public/images/img0'.$col.  '_0' .$row.'.jpg';
                $url = Storage::url($fileName);
                File::create([
                    'url' => $url
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
        $images =  File::all();
        return view('index_images', compact('images'));
    }

    public function splitImages(){

    }
}

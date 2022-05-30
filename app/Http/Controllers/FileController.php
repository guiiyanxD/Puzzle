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

    /*public function storeImageDirty(Request $request){
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);
//        $imagePath = $request->file('file')->store('public/images/');
//        dd($imagePath);
//        $url = Storage::url($imagePath);
//return dd($url);

        File::create([
            'url' => $url
        ]);


        $width = 100;
        $height = 100;
        $source = @imagecreatefromjpeg( $request->file('file') );
//        return dd($request->file('file'),$source);
        $source_width = imagesx( $source );
        $source_height = imagesy( $source );
                for( $col = 0; $col < $source_width / $width; $col++)
                {
                    for( $row = 0; $row < $source_height / $height; $row++)
                    {
//                        $fn ='img0'.$col.  '_' .$row.'.jpg' ;

//                        return dd($uploaded);

                        $imag = UploadedFile::createFromBase(\Intervention\Image\Facades\Image::make($source))->store('public/images');
                        return dd($imag);
                        $image = \Intervention\Image\Facades\Image::make($source)->save("public/images");
                        return dd($image);
                        $destinationPath = 'public/images';


                        $fn ='img0'.$col.  '_' .$row.'.jpg' ;
                        $im = @imagecreatetruecolor( $width, $height );
                        return dd($im);
                        $finalpath = UploadedFile::createFromBase()->store('public/images/');
                        imagecopyresized( $im, $source, 0, 0,
                            $col * $width, $row * $height, $width, $height,
                            $width, $height );
                        imagejpeg($im,$fn);
                        imagedestroy( $im );
                        $finalImage = \Intervention\Image\Facades\Image::make($im);
                         return dd($finalImage);
                         $finalFinalImage = UploadedFile::fake()->createWithContent($fn, $finalImage )->store('public/images/');
                        $uploaded = UploadedFile::createFromBase($finalFinalImage)->storeAs('public/images/', $fn);

                         $url = Storage::url($finalFinalImage);
                        File::create([
                            'url' => $url
                        ]);
                         return dd($url);
                        $uploaded = UploadedFile::createFromBase($finalImage)->createWithContent($fn,\Intervention\Image\Facades\Image::make($im))->storePublicly('public/images/', [$fn]);
                        return dd($uploaded);
                        return dd($uploaded);
                        $request->file('')->store('public/images/');
                        $newImage = UploadedFile::fake()->image($fn)->store('public/images/');
                        return dd(Storage::url($newImage));
                        File::create([
                            'url' => Storage::url($uploaded)
                        ]);
                        return dd($new_file);
                    }
                }
                return redirect()->route('loadImage');
    }
    */
    public function storeImage(Request $request){
        //validating I've received the image
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);

        //Dimensions of each image protion
        $width = 100;
        $height = 100;

        //Image I want to work with
        $source = @imagecreatefromjpeg( $request->file('file') );
        $source_width = imagesx( $source );
        $source_height = imagesy( $source );

        //These "for" are to find coordinate where to split image
        for( $col = 0; $col < $source_width / $width; $col++)
        {
            for( $row = 0; $row < $source_height / $height; $row++)
            {
                //Name to the splited images
                $filePath = Storage::path('public\images\img0'.$col.  '_0' .$row.'.jpg');

                //Creating the new Image
                $im = @imagecreatetruecolor( $width, $height );

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

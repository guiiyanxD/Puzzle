@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 align-content-center">
                <div  class="" style="">
                    <canvas style="border: 1px solid; width: 100%" id="canvas">
                    </canvas>
                    <img src="{{asset('/storage/images/img00_01.jpg')}}" style="display: none" alt="" width="100" height="100" id="image">
                </div>
            </div>
        </div>
    </div>
    <script>
        canvas = document.getElementById("canvas");
        context = canvas.getContext("2d");
        // canvas.offsetWidth = pixelWidth * devicePixelRatio;
        // context.fillStyle = "#123456";

        // context.fill(30,10,50,100);

        // console.log(canvas.offsetWidth);
        // console.log(canvas.offsetHeight);

        var image = document.getElementById("image");
        context.drawImage(image, 33, 71, 104, 124, 21, 20, 87, 104);
        // context.globalAlpha = 0.4;

        // context.lineWidth = devicePixelRatio;
        // context.moveTo(100,0);
        // context.lineTo(100, canvas.offsetHeight);
        // context.stroke();

    </script>
@endsection

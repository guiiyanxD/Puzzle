@extends('layouts.app')
@section('content')
    <style>
        .my_placeholder{
            background-color: #25cff2;
            display: inline-block;
            cursor: pointer;
            vertical-align: middle;
            opacity: 0.4;
        }

        .my_placeholder_replaced{
            display: inline-block;
            vertical-align: middle;
            opacity: 1;
        }
        .hover{
            background-color: #fd7e14;
        }

        .image-cover{
            object-fit: cover;
        }
    </style>
    <div class="container" style="padding-top: 15px;">
        <div class="row" style="height: 70%">
            <div class="col-lg-3">
                <div class="row mb-3">
                    <div class="p-0">
                        <div class="card ">
                            <div class="card-header">
                                <h5>{{__('Imagen completa')}}</h5>
                            </div>
                            <div class="card-body" style="">
                                <img class="img-fluid image-cover" src="{{asset($ful_image->url)}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="container p-0 m-0" style="{{-- border: 1px solid #000000; border-radius: 5px--}}" >
                        <div class="card m-0 " style="max-height: 250px; border-radius: 5px">
                            <div class="card-header">
                                <h5>Piezas</h5>
                            </div>
                            <div class="card-body p-0 overflow-auto" style="overflow-y: scroll">
                                <div class="row m-0" id="pieces">
{{--                                    {{ shuffle($images) }}--}}
                                    @foreach($images as $cat)
                                        <div class="col-lg-3 p-0 " style="cursor: move" draggable="true" >
                                            <img class=" img-fluid img-thumbnail mx-auto p-1" id="{{$cat->y_index . $cat->x_index}}" src="{{asset($cat->url)}}" alt="" style="display: block; object-fit: cover;" >
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col">
{{--                    TODO: EN ESTE DIV, EL HEIGTH DEBE SER MULTIPLICADO POR LA CANTIDAD DE FILAS QUE TIENE EL JUEGO--}}
                <div class="row justify-content-around" style="border: 1px #f0f0f0">
                        <div class="   d-block " id="puzzle">

                        </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="text-white">
                <h3>Puntuacion:</h3>
                <div >
                    <h5 id="score">0</h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="text-white">
                <h3>Movimientos:</h3>
                <div >
                    <h5 id="movement">0</h5>
                </div>
            </div>
        </div>
    </div>


    <script>
        const userID = {{ \Illuminate\Support\Js::from( \Illuminate\Support\Facades\Auth::user()->id) }};
        const puzzle = document.getElementById('puzzle');
        const piezas_container = document.getElementById('pieces');

        const game = {{\Illuminate\Support\Js::from($game)}};
        const cant_cols = game[0].cols;
        const cant_rows = game[0].rows;

        const pieces = {{\Illuminate\Support\Js::from($images)}};
        const width = pieces[1].width;
        const height = pieces[1].height;


        for( let i = 0; i <cant_rows; i++ ){
            const divv = document.createElement("div");
            divv.classList.add('row');
            puzzle.appendChild(divv);

            for(let j =0 ; j<cant_cols;j++){
                const div = document.createElement("div");
                div.classList.add('col');
                div.setAttribute('id',i.toString() + j.toString());
                div.classList.add('my_placeholder');
                div.style.width = width.toString() + 'px';
                div.style.height = height.toString() + 'px';
                div.style.margin = '1px';
                div.style.border = '1px solid #000000';
                puzzle.appendChild(div);
            }
        }

        piezas_container.addEventListener('dragstart',e=>{
           e.dataTransfer.setData('id', e.target.id);
        });
        puzzle.addEventListener('dragover', e =>{
            e.preventDefault();
            e.target.classList.add('hover');
        });

        puzzle.addEventListener('dragleave', e=>{
           e.target.classList.remove('hover');
        });

        puzzle.addEventListener('drop',e =>{
            e.target.classList.remove('hover');

            const id = e.dataTransfer.getData('id'); //Obtengo el Id de la pieza por que me esta transfiriendo la data que sale con dragStart
            // console.log('Pieza: '+id);
            // console.log('Cajon:'+e.target.id); //Obtengo el id del cajon por que esta escuchando los eventos de los cajones
            if( e.target.id === id ){
                e.target.classList.remove('my_placeholder');
                e.target.classList.add('my_placeholder_replaced');
                e.target.classList.add('image_cover');
                e.target.appendChild(document.getElementById(id));
                addScore(game[0].id, userID);
                addMovement(game[0].id, userID);


            }else{
                addMovement(game[0].id, userID);
                subScore(game[0].id, userID);

            }
        });


        function addScore($gameID, $userID){
            $.ajax({
                type:"Put",
                url:"/game/addScore",
                data:{
                    game_id: $gameID,
                    user_id: $userID,
                },
                success: function(data){
                    $('#score').html(data.toString());
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });
        }

        function addMovement($gameID, $userID){
            $.ajax({
                type:"Put",
                url:"/game/addMovement",
                data:{
                    game_id: $gameID,
                    user_id: $userID,
                },
                success: function(data){
                    $('#movement').html(data.toString());
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });
        }

        function subScore($gameID, $userID){
            $.ajax({
                type:"Put",
                url:"/game/subScore",
                data:{
                    game_id: $gameID,
                    user_id: $userID,
                },
                success: function(data){
                    $('#score').html(data.toString());
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });
        }
    </script>
@endsection

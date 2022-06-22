@extends('layouts.app')
@section('content')
    <style>

    </style>
    <div class="container" style="padding-top: 15px;">
        <div class="row">
            <div class=" col text-white">
                <h5>Puntuacion:
                    @if(isset($passedScore))
                        <span id="score">{{$score}}</span>
                    @else
                        <span id="score">0</span>
                    @endif

                </h5>
            </div>
            <div class="col text-white">
                <h5>Movimientos:

                    @if(isset($passedMovements))
                        <span id="movement">{{$passedMovements}}</span>
                    @else
                        <span id="movement">0</span>
                    @endif
                </h5>
            </div>
            <div class=" col text-white">
                <h5>Jugadores en la sesion: <span id="online"> </span> </h5>

            </div>
        </div>
        <div class="row" style="height: 70%">
            <div class="col-lg-3">
                <div class="row mb-3">
                    <div class="p-0">
                        <div class="card ">
                            <div class="card-header">
                                <h5>{{__('Imagen completa')}}</h5>
                            </div>
                            <div class="card-body" style="">
                                <img class="img-fluid image-cover" src="{{ asset($ful_image[0]->url)}}" alt="">
                                <script>
                                    console.log({{\Illuminate\Support\Js::from( asset($ful_image[0]->url))}})
                                </script>
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
                                            <img class=" img-fluid img-thumbnail mx-auto p-1" id="{{$cat->y_index . $cat->x_index}}" src="{{ asset($cat->url) }}" alt="" style="display: block; object-fit: cover;" >
                                            <script>
                                                console.log({{\Illuminate\Support\Js::from(asset($cat->url)) }})
                                            </script>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row justify-content-around" style="border: 1px #f0f0f0">
                        <div class="   d-block " id="puzzle">

                        </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        /*
        * Script para el contador de usuarios que esta en linea
        * */
        let onlineUsers = 0;
        const userEvent = {{\Illuminate\Support\Js::from(\Illuminate\Support\Facades\Auth::user()->id)}};
        const gameEvent = {{\Illuminate\Support\Js::from($game)}};


        function update_online_counter() {
            document.getElementById('online').textContent = '' + onlineUsers;
        }

        window.Echo.join('game.'+ gameEvent[0].id)
            .here((users) => {
                onlineUsers = users.length;
                // console.log('Estan aqui: ' + users.toString())
                update_online_counter();
            })
            .joining((user) => {
                onlineUsers++;
                // console.log("acaba de entrar:" +  user.toString())
                update_online_counter();
            })
            .leaving((user) => {
                onlineUsers--;
                // console.log('se fue: ' +user.toString())
                update_online_counter();
            })

    </script>

    <script>

        /*
        * Scrip para el control de los eventos drag and drop, addScore, subScore, addMovement y reDrawGame
        * Tambien se crean la celda de cada imagen
        * */
        const userID = {{ \Illuminate\Support\Js::from( \Illuminate\Support\Facades\Auth::user()->id) }};
        const puzzle = document.getElementById('puzzle');
        const piezas_container = document.getElementById('pieces');
        let cant_piezas = piezas_container.childElementCount;

        const game = {{\Illuminate\Support\Js::from($game)}};
        const cant_cols = game[0].cols;
        const cant_rows = game[0].rows;

        const pieces = {{\Illuminate\Support\Js::from($images)}};
        const width = pieces[1].width;
        const height = pieces[1].height;

        let globalIDPieza = 0;//Origen
        let globalIDCajon = 0;//Destino

        for( let i = 0; i <cant_rows; i++ ){
            const divv = document.createElement("div");
            divv.classList.add('row');
            puzzle.appendChild(divv);

            for(let j =0 ; j<cant_cols;j++){
                const div = document.createElement("div");
                div.classList.add('col');
                div.setAttribute('id','container-'+ i.toString() + j.toString());
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
            globalIDCajon = e.target.id.split('-');//ID del div donde estoy soltando la pieza
            globalIDPieza = id
            console.log('goblalIDCajon: ' + globalIDCajon[1]);
            // setPieceCorrectPlace(lugarPiezaID, piezaID)
            if( globalIDCajon[1] === id ){
                e.target.classList.remove('my_placeholder');
                e.target.classList.add('my_placeholder_replaced');
                e.target.classList.add('image_cover');
                e.target.appendChild(document.getElementById(id));
                addScore(game[0].id, userID);
                addMovement(game[0].id, userID);
                sentReDrawPuzzle(e.target.id, game[0].id)//ID Container de la pieza, y el id del juego
                cant_piezas = cant_piezas-1;
                if(cant_piezas === 0){
                    winner(game[0].id);
                }

            }else{
                addMovement(game[0].id, userID);
                subScore(game[0].id, userID);
            }
        });

        //Cada vez que se ejecuta el drop, se envia una peticion post con el ID del div donde tiene que ir la pieza,
        //y con el codigo de aqui abajo se reacciona al evento y se actualiza en las demas sesiones
        window.Echo.join('game.' + game[0].id) //aqui se anadio el gameid[0]
            .listen('MovementsTrackEvent',($data)=>{
                reDrawGame($data.idCeldaImage);
            });

        function sentReDrawPuzzle($idCeldaImage, $gameID){
            $.ajax({
                type:"Post",
                url:"/game/reDrawGame",
                data:{
                    idCeldaImage: $idCeldaImage,
                    game_id : $gameID,
                }/*,
                success: function(data){
                    console.log(data.toLocaleString());
                }*/,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });
        }
        function reDrawGame($idCeldaImage){
            let piezaID = $idCeldaImage.split('-');

            divPieza = document.getElementById(piezaID[1]);
            divContainer = document.getElementById($idCeldaImage);

            divContainer.classList.add('my_placeholder_replaced');
            divContainer.classList.add('image_cover');
            divContainer.appendChild(divPieza);
        }
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
            // console.log(ex);
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
        function winner($gameID){
            let ex = $.ajax({
                type:"Put",
                url:"/game/setWinner",
                data:{
                    game_id: $gameID,
                },
                success: function(data){
                    alert("Un trofeo más: " + data.toString() + ". Has Ganado");
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });
        }

    </script>
@endsection

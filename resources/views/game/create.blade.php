@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row mt-4 justify-content-around">
            <div class="col-md-12">
                <div class="card bg-dark text-white text-center " >
                    <div class="card-header">
                        <h2>
                            {{ __('Configurar dificultad del juego') }}
                        </h2>
                    </div>
                    <div class="card-body">
                        <form action="{{route('storeImage')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row m-0">
                                <div class="col-lg-12">
                                    <div class="row " >
                                        <div class="col form">
                                            <label class="form-label" for="rows">{{__('Numero de filas')}}</label>
                                            <input class="form-control" type="number" name="rows" placeholder="{{__('Escribe un numero')}}">
                                        </div>
                                        <div class="col form">
                                            <label class="form-label" for="cols">{{__('Numero de columnas')}}</label>
                                            <input class="form-control" type="number" name="cols" placeholder="{{__('Escribe un numero')}}">
                                        </div>
                                        <div class="col form ">
                                            <label class="form-label" for="cols">{{__('Seleccione la imagen')}}</label>
                                            <input type="file" class="form-control" name="file" accept="image/jpeg">
                                            @error('file')
                                            <small>{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row p-4 justify-content-around">
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col form">
                                            <button class=" btn btn-outline-primary" type="submit">{{__('Que comience el juego')}}</button>
                                        </div>
                                        <div class="col form">
                                            <button class=" btn btn-outline-danger" type="submit">{{__('Volver al inicio')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row text-center mt-3">
            <h5 class="text-white">{{__('o tambien, retoma un juego' )}}</h5>
        </div>
        <div class="row mt-5 justify-content-center">
            <div class="col-md-8">
                <div class="card bg-dark text-white text-center " >
                    <div class="card-header">{{ __('Configurar dificultad del juego') }}</div>
                    <div class="card-body">
                        <form action="{{route('storeImage')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col">
                                        <label for="">Numero de filas</label>
                                        <input type="number" name="rows">
                                    </div>
                                    <div class="col">
                                        <label>Numero de columnas</label>
                                        <input type="number" name="cols">
                                    </div>
                                    <div class="col">
                                        <input type="file" name="file" accept="image/*">
                                        @error('file')
                                        <small>{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit">Load Image</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

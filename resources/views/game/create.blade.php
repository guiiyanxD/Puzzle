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
                        <form action="{{route('storeGame')}}" method="post" enctype="multipart/form-data">
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
            <h5 class="text-white">{{__('ó' )}}</h5>
        </div>
        <div class="row mt-2 justify-content-center">
            <div class="col-md-8">
                <div class="card bg-dark text-white text-center " >
                    <div class="card-header">{{ __('Unete a una partida ya creada') }}</div>
                    <div class="card-body">
                        <form action="{{route('joinGame')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col form">
                                    <label class="form'label" for="code_invitation">Ingresa el codigo de invitacion</label>
                                    <input type="text" class="form-control" name="code_invitation">
                                    @error('code_invitation')
                                    <small class="text-white">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row py-4">
                                <div class="col form">
                                    <button class=" btn btn-outline-primary" type="submit">{{__('Unete')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

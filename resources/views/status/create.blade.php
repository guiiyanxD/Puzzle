@extends('layouts.app')
@section('content')
<h1>Aqui sera la vista para crear un nuevo estado del juego, por defecto: en curso y finalizado.</h1>
    <div class="container">
        <div class="row justify-content-lg-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5> {{__('Agregar nuevo estado')}} </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('status.store')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <label for="">Nombre</label>
                                    <input type="text" name="name">
                                </div>
                                <div class="col">
                                    <label for="description" class="my-auto">Descripcion</label>
                                    <input type="text" class="my-auto" name="description">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <button type="submit" class="btn-primary">Guardar</button>
                                </div>
                                <div class="col-lg-4">
                                    <a href="{{route('loadImage')}}" class="btn-danger" type="button">Cancelar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

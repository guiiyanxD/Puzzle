@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <form action="{{route('storeImage')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
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
                    </div>
                    <button type="submit">Load Image</button>
                </form>
            </div>
        </div>
    </div>
@endsection

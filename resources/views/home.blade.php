@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card bg-dark text-white text-center " style="width: 414px; height: 237px; object-fit: cover" >
                <div class="card-header">{{ __('Nuevo juego') }}</div>
                <a href="{{ route('createGame') }}">
                    <img class="card-img-top img-fluid" style="object-fit: cover"  src="{{asset('/storage/dashboard/startGame2.jpg')}}" alt="">
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center bg-dark text-white" style="width: 414px; height: 237px; object-fit: cover">
                <div class="card-header">{{ __('Cargar Juego') }}</div>
                <a href="{{ route('savedGames',[\Illuminate\Support\Facades\Auth::id()]) }}">
                    <img class="card-img-top img-fluid"  src="{{asset('/storage/dashboard/resumeGame.jpg')}}" alt="">
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white text-center" style="width: 414px; height: 237px; object-fit: cover">
                <div class="card-header">{{ __('Ranking') }}</div>

                <a href="{{ route('loadImage') }}">
                    <img class="card-img-top img-fluid" src="{{asset('/storage/dashboard/rankinGame.jpg')}}" alt="">
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

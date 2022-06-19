@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-start">
            <div class="col-lg-12">
                <h2 class="text-white">{{__('Juegos pendientes:')}}</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            @for( $i = 0; $i < count($games); $i++)
                <div class="col-lg-4">
                    <div class="card text-white bg-dark mb-3" >
                        <div class="card-header">Header</div>
                        <a href="{{route('startGame',[$games[$i]->id])}}">
                            <img class="card-img-top"  src="{{$games[$i]->portrait->url}}" alt="">
{{--                            <p>{{ }}</p>>--}}
                        </a>
                        <div class="card-body d-inline">
                            <div class="card-title">
                                <h6 class="pb-0" style=" color: #9fd1ff"> <strong>{{ __('Codigo de invitacion: ')}}</strong> </h6>
                                <small>
                                    <strong style="color: #9fd1ff">
                                        {{$games[$i]->code_invitation}}
                                    </strong>
                                </small>
                            </div>
                            <div class="card-text">
                                <p>
                                    <strong style="color: #e388ff">{{__('Dificultad: ')}}</strong> {{$games[$i]->cols}} {{__('X')}} {{$games[$i]->rows}}
                                </p>
                            </div>
                            <div class="card-text text-center">
                                @if( $games[$i]->status->id == 1)
                                    <p style="background-color: #198754; border-radius: 50px">
                                        {{$games[$i]->status->name}}
                                    </p>
                                @else
                                    <p style="background-color: #dc3545; border-radius: 50px">
                                        {{$games[$i]->status[$i]->name}}
                                    </p>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
           @endfor
        </div>
    </div>
@endsection

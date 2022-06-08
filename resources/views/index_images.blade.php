@extends('layouts.app')
@section('content')
    <div class="container" style="padding-top: 35px;">

        <div class="row">
            <div class="col-lg-4">
                <div class="card" style="height: 35rem">
                    <div class="card-body " style="height: 80%; overflow-y: scroll">

                        <div class="container" >
                            <div class="row">
                                @foreach($images as $cat)
                                    <div class="col-lg-3 p-0 m-0" draggable="true" style="cursor: move">
                                        <img class="img-fluid img-thumbnail mx-auto"  src="{{asset($cat->url)}}" alt="" style="display: block; object-fit: cover">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <img>

                        </img>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

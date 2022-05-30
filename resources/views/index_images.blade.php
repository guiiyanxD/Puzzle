@extends('layouts.app')
@section('content')
    <div class="container" style="padding-top: 35px;">
        <div class="row">
            @foreach($images as $cat)
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <img class="img-fluid img-thumbnail" src="{{asset($cat->url)}}" alt="" style="display: block; object-fit: cover">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection

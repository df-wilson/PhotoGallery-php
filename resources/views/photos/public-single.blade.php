@extends('layouts.app')

@section('content')
    <div id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <div id="img-title" onmousedown="editTitle()">
                        <h1 id="img-title-text" class="text-center">{{$name}}</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-10">
                    <div class="img-area">
                        <img src="{{$src}}" alt="{{$name}}" class="responsive-image">
                        <p>
                            <b>Description:</b><br>
                            {{$description}}
                        </p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div id="keyword-div">
                        <h3>Keywords</h3>
                        <div id="keyword-div">
                            @foreach($keywords as $keyword)
                                <p><button class="btn btn-sm">{{$keyword->name}}</button></p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

@endsection
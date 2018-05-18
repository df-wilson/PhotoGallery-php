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
                <div class="col-xs-12">
                    <div class="img-area">
                        <img src="{{$src}}" alt="{{$name}}" class="responsive-image">
                        <p>
                            <b>Description:</b><br>
                            {{$description}}
                        </p>

                        <p><b>Keywords</b></p>
                        <div id="keyword-div">
                            @foreach($keywords as $keyword)
                                <button class="btn">{{$keyword->name}}</button>
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
@extends('layouts.app')

@section('content')
    <div id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <div id="img-title" onmousedown="editTitle()">
                        <h1 id="img-title-text" class="text-center">{{$name}}</h1>
                    </div>
                    <div id="img-title-edit">
                        <input id="img-title-input" type="text" required size="30">
                        <button id="img-title-update-btn" class="btn btn-primary btn-sm" type="button" onclick="submitTitle({{$id}})">Update</button>
                        <button id="img-title-cancel-btn" class="btn btn-primary btn-sm" type="button" onclick="cancelUpdateTitle()">Cancel</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="img-area">
                       <img src="{{$src}}" alt="{{$name}}" class="responsive-image">
                        <textarea id="desc-text" class="img-desc" name="textarea" rows="3" cols="40" onfocus="showUpdateButton()">{{$description}}</textarea>
                        <button id="desc-update-button" class="btn btn-primary btn-sm" type="button" onclick="submitDescription({{$id}})" style="display: none">Update</button>
                        <h3>Keywords <button class="btn btn-xs btn-primary" type="button" onclick="showAddKeyword()">+</button></h3>
                        <div id="add-keyword-form">
                            <input id="keyword-input" type="text" size="20">
                            <button id="keyword-update-btn" class="btn btn-primary btn-sm" type="button" onclick="submitKeyword({{$id}})">Add</button>
                            <button id="keyword-done-btn" class="btn btn-primary btn-sm" type="button" onclick="doneAddKeyword()">Done</button>
                        </div>
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
<script src="/js/photoSingle.js"></script>
@endsection
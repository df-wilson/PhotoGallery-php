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
                <div class="col-xs-12 col-md-10">
                    <div class="img-area" style="text-align: center">
                       <img src="{{$src}}" alt="{{$name}}" class="responsive-image">
                    </div>

                    <h3>Description</h3>
                    <textarea id="desc-text" class="img-desc form-control" name="textarea" rows="4" cols="40" onfocus="showUpdateButton()">{{$description}}</textarea>
                    <button id="desc-update-button" class="btn btn-primary btn-sm" type="button" onclick="submitDescription({{$id}})" style="display: none">Update</button>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div id="keyword-div">
                        <h3>Keywords <button class="btn btn-xs btn-primary" type="button" onclick="showAddKeyword()">+</button></h3>
                        <div id="add-keyword-form">
                            <input id="keyword-input" type="text" size="20" list="keyword-options">
                            <datalist id="keyword-options">
                            </datalist>
                            <button id="keyword-update-btn" class="btn btn-primary btn-sm" type="button" onclick="submitKeyword({{$id}})">Add</button>
                            <button id="keyword-done-btn" class="btn btn-primary btn-sm" type="button" onclick="doneAddKeyword()">Done</button>
                        </div>
                        @foreach($keywords as $keyword)
                            <div id="keyword{{$keyword->id}}">
                                <p>
                                    <button class="btn" onclick="showAllForKeyword({{$keyword->name}})">{{$keyword->name}}</button>
                                    <button class="btn btn-xs btn-danger" type="button" onclick="removeKeyword({{$id}},{{$keyword->id}})">x</button>
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div id="public-toggle-div">
                        <input type="checkbox" id="public-checkbox" name="public-checkbox" @if($is_public) checked @endif onchange="submitTogglePublic({{$id}})">
                        <label for="public-checkbox">Allow public</label>
                    </div>
                    <div id="navigation-section">
                        <button class="btn" onclick="showPreviousPhoto({{$id}})">⇦</button>
                        <button class="btn" onclick="showNextPhoto({{$id}})">⇨</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
<script src="/js/photoSingle.js"></script>
@endsection
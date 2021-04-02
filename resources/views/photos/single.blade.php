@extends('layouts.app')

@section('content')
    <div id="app">
        <div class="row">
            <div class="col-12">
                <div id="img-title" onmousedown="editTitle()">
                    <h1 id="img-title-text" class="text-center">{{$name}}</h1>
                </div>
                <div id="img-title-edit">
                    <input id="img-title-input" type="text" required size="30" onkeypress="submitTitleOnEnter(event, {{$id}})">
                    <button id="img-title-update-btn" class="btn btn-primary btn-sm" type="button" onclick="submitTitle({{$id}})">Update</button>
                    <button id="img-title-cancel-btn" class="btn btn-primary btn-sm" type="button" onclick="cancelUpdateTitle()">Cancel</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-9 col-lg-10">
                <div class="img-area" style="text-align: center">
                   <img src="{{$src}}" alt="{{$name}}" class="responsive-image">
                </div>

                <h2>Description</h2>
                <textarea id="desc-text" class="img-desc form-control" name="textarea" rows="4" cols="40" onfocus="showUpdateButton()" onkeydown="setDescriptionUpdateCheck(false)">{{$description}}</textarea>
                <button id="desc-update-button" class="btn btn-primary btn-sm" type="button" onclick="submitDescription({{$id}})" style="display: none">Update</button>

                <span id="desc-update-check">✔</span>
            </div>
            <div class="col-12 col-md-3 col-lg-2">
                <div id="keyword-div">
                    <h2>Keywords <button class="btn btn-xs btn-primary keyword-edit" type="button" onclick="showAddKeyword()">+</button><span id="keyword-edit-link">(<a href="#edit" onclick="showKeywordEditBtns()">edit</a>)</span></h2>
                    <div id="add-keyword-form">
                        <input id="keyword-input" type="text" size="20" list="keyword-options" onkeypress="checkKeywordInputForEnter(event, {{$id}})">
                        <datalist id="keyword-options">
                        </datalist>
                        <button id="keyword-update-btn" class="btn btn-primary btn-sm" type="button" onclick="submitKeyword({{$id}})">Add</button>
                        <button id="keyword-done-btn" class="btn btn-primary btn-sm" type="button" onclick="doneAddKeyword()">Done</button>
                    </div>
                    @foreach($keywords as $keyword)
                        <div id="keyword{{$keyword->id}}" class="mb-1">
                            <button class="btn btn-xs btn-light" onclick="showAllForKeyword({{$keyword->id}})">{{$keyword->name}}</button>
                            <button class="btn btn-xs btn-danger keyword-edit" type="button" onclick="removeKeyword({{$id}},{{$keyword->id}})">x</button>
                        </div>
                    @endforeach
                </div>

                <div id="metadata-section">
                    <h2>Photo Info</h2>
                    <table>
                        <tr>
                            <td>Date:</td>
                            <td>{{str_replace(':', '-', substr($photo_datetime,0,10))}}</td>
                        </tr>
                        <tr>
                            <td>Camera:</td>
                            <td>{{$camera}}</td>
                        </tr>
                        <tr>
                            <td>Model:</td>
                            <td>{{$model}}</td>
                        </tr>
                    </table>
                </div>

                <div id="public-toggle-div">
                    <input type="checkbox" id="public-checkbox" name="public-checkbox" @if($is_public) checked @endif onchange="submitTogglePublic({{$id}})">
                    <label for="public-checkbox">Allow public</label>
                </div>

                <div id="navigation-section">
                    <button class="btn btn-light" onclick="showPreviousPhoto({{$id}})">⇦</button>
                    <button class="btn btn-light" onclick="showNextPhoto({{$id}})">⇨</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
<script src="/js/photoSingle.js"></script>
@endsection
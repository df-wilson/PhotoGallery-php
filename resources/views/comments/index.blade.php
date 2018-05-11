@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="app">
                    <comments></comments>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="/js/comments.js"></script>
@endsection
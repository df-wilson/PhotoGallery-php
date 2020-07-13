@extends('layouts.app')

@section('content')
    <div class="container">
        <div id="app">
            <photo-home></photo-home>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="/js/photoHome.js"></script>
@endsection
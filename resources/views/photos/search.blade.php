@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div id="app">
                    <photo-search></photo-search>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="/js/photoSearch.js"></script>

@endsection
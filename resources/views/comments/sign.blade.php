@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <comment-form></comment-form>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="/js/commentForm.js"></script>
@endsection
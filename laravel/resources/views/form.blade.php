@extends('layout.base')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('title', '1行日記サイト/投稿フォーム')
@section('content')
<div class="container mt-5">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <form action="/articles" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="content">1行日記</label>
            <input type="text" class="form-control" id="content" name="content" value="{{ old('content', $article->content ?? '') }}" placeholder="1行日記を入力してください">
            @error('content')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="image">画像アップロード</label>
            <input type="file" class="form-control-file" id="image" name="image" value="">
            @if (isset($article) && $article->image)
                <img class="img-fluid" src="{{ asset('storage/'.$imageDir.'/'.$article->image) }}" alt="画像記事">
            @endif
            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="row">
            @if (isset($article))
                <div class="col-3">
                    <button type="button" class="btn btn-danger" id="delete" data-id="{{ $article->id }}">削除</button>
                </div>
            @endif
            <div class="col-3 ml-auto text-right">
                <button type="submit" class="btn btn-primary">{{ $submit }}</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
    <script src="{{ asset('/js/articles.js') }}"></script>
@endsection

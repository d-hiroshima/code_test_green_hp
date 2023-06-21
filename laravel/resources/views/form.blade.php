@extends('layout.base')

@section('title', '1行日記サイト/投稿フォーム')
@section('content')
<div class="container mt-5">
    <form action="/articles" method="post">
        @csrf
        <div class="form-group">
            <label for="content">1行日記</label>
            <input type="text" class="form-control" id="content" name="content" value="{{ old('content', $article->content ?? '') }}" placeholder="1行日記を入力してください">
            @error('content')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="imageUpload">画像アップロード</label>
            <input type="file" class="form-control-file" id="imageUpload" name="image">
            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">投稿</button>
    </form>
</div>
@endsection

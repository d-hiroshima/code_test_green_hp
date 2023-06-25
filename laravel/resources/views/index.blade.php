@extends('layout.base')

@section('title', '1行日記サイト')
@section('content')
<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
    <div class="container">
        <h1 class="text-center">1行日記サイト</h1>
        <div class="row mb-4 justify-content-center">
            <div class="col-lg-6">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="d-flex justify-content-center my-5">
                    <a href="{{ route('articles.create') }}" class="btn btn-primary">新規投稿</a>
                </div>
                @if ($articles->isEmpty())
                    <p class="text-center">現在記事はありません。</p>
                @else
                    @foreach ($articles as $article)
                        <div class="card my-4">
                            <div class="card-body">
                                <span class="card-title">{{ $article->content }}</span>
                            </div>
                            @if ($article->image)
                                <img class="card-img-top p-5" src="{{ asset('storage/'.$imageDir.'/'.$article->image) }}" alt="">
                            @endif
                            <div class="card-footer text-right">
                                <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i>編集</a>
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-center">
                        {{ $articles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection

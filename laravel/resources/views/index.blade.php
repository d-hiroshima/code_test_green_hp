@extends('layout.base')

@section('title', '1行日記サイト')
@section('content')
<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
    <div class="container">
        <h1 class="text-center">1行日記サイト</h1>
        <div class="row mb-4 justify-content-center">
            <div class="col-lg-6">
                @if ($articles->isEmpty())
                    <p class="text-center">現在記事はありません。</p>
                @else
                    @foreach ($articles as $article)
                        <div class="card my-4">
                            <div class="card-body">
                                <span class="card-title">{{ $article->content }}</span>
                            </div>
                            <img class="card-img-top" src="{{ $article->image }}" alt="">
                            <div class="card-footer text-right">
                                <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-primary">編集</a>
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

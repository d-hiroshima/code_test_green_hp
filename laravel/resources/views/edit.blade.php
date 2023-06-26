<form action="/articles/{{ $article->id }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('form', ['submit' => '更新'])
</form>

<form action="/articles" method="post" enctype="multipart/form-data">
    @csrf
    @include('form', ['submit' => '投稿'])
</form>

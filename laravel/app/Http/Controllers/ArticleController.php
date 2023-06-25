<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;

class ArticleController extends Controller
{
    // 画像保存先ディレクトリ
    const IMAGE_DIR = 'images/articles';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(5);
        $imageDir = self::IMAGE_DIR;
        return view('index', compact('articles', 'imageDir'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreArticleRequest $request
     * @return Response
     */
    public function store(StoreArticleRequest $request)
    {
        try {
            DB::beginTransaction();
            Article::create([
                'content' => $request->content,
                'image' => $this->imageUpload($request),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return Redirect::route('articles.create')->with('fail', '投稿に失敗しました。error: '.$e->getMessage());
        }

        return Redirect::route('articles.index')->with('success', '投稿が完了しました。');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 現在は使用しないがshow()を残さないとリソースコントローラーが機能しなくなるので残しています。
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = (new Article)->find($id);
        if (!$id || !$article) {
            return Redirect::route('articles.index');
        }
        return view('form', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $article = (new Article)->find($id);
        $article->content = $request->content;
        $article->save();
        return $this->index();

        try {
            DB::beginTransaction();
            Article::create([
                'content' => $request->content,
                'image' => $this->imageUpload($request),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return Redirect::route('articles.edit')->with('fail', '投稿に失敗しました。error: '.$e->getMessage());
        }

        return Redirect::route('articles.index')->with('success', '投稿が完了しました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$id) {
            return response()->json(['redirect' => route('articles.index'), 'status' => 'fail']);
        }

        $article = (new Article)->find($id);
        if (!$article) {
            return response()->json(['redirect' => route('articles.index'), 'status' => 'fail']);
        }

        $article->delete();
        return response()->json(['redirect' => route('articles.index'), 'status' => 'success']);
    }

    /**
     * 画像アップロード
     *
     * @param Request $request
     * @return string
     */
    private function imageUpload(Request $request)
    {
        if ($request->image === null) return '';
        $filename = $request->image->getClientOriginalName();
        $request->image->storeAs(self::IMAGE_DIR, $filename, 'public');
        return $filename;
    }
}

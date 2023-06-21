<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;

class ArticleController extends Controller
{
    // 画像保存先ディレクトリ
    const IMAGE_DIR = 'images/artcles';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $articles = Article::paginate(5);
        return view('index', compact('articles'));
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

            return $this->index();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = (new Article)->find($id);
        if (!$article) return $this->index();

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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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

    /**
     * 画像の取得
     *
     * @return string
     */
    private function getImage()
    {

    }
}

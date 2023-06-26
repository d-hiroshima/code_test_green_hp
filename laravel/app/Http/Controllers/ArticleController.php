<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
        return view('create');
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
                'image' => $this->uploadImage($request),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return Redirect::route('articles.create')->with('fail', '投稿に失敗しました。error: '.$e->getMessage());
        }

        return Redirect::route('index')->with('success', '投稿が完了しました。');
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
            return Redirect::route('index');
        }

        $imageDir = self::IMAGE_DIR;
        return view('edit', compact('article', 'imageDir'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreArticleRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $article = (new Article)->find($id);
            if ($article) {
                $article->content = $request->content;
                // 画像の変更があればアップデートする
                if ($request->image !== null) {
                    $article->image = $this->uploadImage($request);
                }

                $article->save();
                DB::commit();

                return Redirect::route('index')->with('success', '編集が完了しました。');
            } else {
                throw new \Exception('記事が見つかりませんでした');
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $imageDir = self::IMAGE_DIR;
            return Redirect::route('articles.edit', ['article' => $id, 'imageDir' => $imageDir])->with('error', '投稿に失敗しました。error: '.$e->getMessage());
        }
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
            return response()->json(['redirect' => route('index'), 'status' => 'fail']);
        }

        $article = (new Article)->find($id);
        if (!$article) {
            return response()->json(['redirect' => route('index'), 'status' => 'fail']);
        }

        $image = $article->image;
        $article->delete();
        $this->removeImage($image);
        return response()->json(['redirect' => route('index'), 'status' => 'success']);
    }

    /**
     * 画像アップロード
     *
     * @param Request $request
     * @return string
     */
    private function uploadImage(Request $request)
    {
        if ($request->image === null) return '';

        // ファイル名を設定
        $file = $request->image;
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;

        $request->image->storeAs(self::IMAGE_DIR, $filename, 'public');
        return $filename;
    }

    /**
     * 画像をストレージから削除
     *
     * @param string $filename
     * @return boolean
     */
    private function removeImage($filename)
    {
        if (!$filename) return false;

        $path = self::IMAGE_DIR .'/'. $filename;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return true;
    }
}

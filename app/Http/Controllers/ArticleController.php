<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Audio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return view('articles.index');
        $articles = Article::all();

        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $audio = new Audio($request->all());
        $audio->user_id = $request->user()->id;
        $audio->article_id = 1;
        $audio->status = 1;

        $file = $request->file('image');
        $audio->file = date('YmdHis') . '_' . $file->getClientOriginalName();

        $audio->save();

        // 画像アップロード
        Storage::putFileAs('images/posts', $file, $audio->file);

        return redirect()
            ->route('articles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('articles.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audio;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AudioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('audios.index');
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
        $audio = new Audio();
        $audio->status = 0;
        $audio->file = "test";
        $audio->article_id = 1;
        $audio->user_id = 1;
        $audio->save();

        $audio = new Audio($request->all());
        // $audio->user_id = $request->user()->id;
        $audio->user_id = 1;
        $audio->article_id = 1;
        $audio->status = 1;

        $file = $request->file('blob');
        $audio->file = date('YmdHis') . '_' . $file->getClientOriginalName();

        $audio->save();

        // 画像アップロード
        Storage::putFileAs('audios', $file, $audio->file);

        return redirect()
            ->route('audios.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $article = Article::with(['user'])->find($id);
        // $comments = $article->comments()->latest()->get()->load(['user']);
        $article = Article::find($id);
        $audios = DB::table('audios')->where('book_id', $article->book_id)->get();
        dd($audios);
        return view('articles.show', compact('article'));
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

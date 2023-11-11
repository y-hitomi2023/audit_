<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
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
    public function store(Request $request, String $id)
    {

        $audio = new Audio($request->all());
        $audio->user_id = $request->user()->id;
        $audio->book_id = 1;
        $audio->article_id = $id;
        // $audio->article_id = 1;
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

        // $article = Article::find($id);
        // $audios = DB::table('audio')->where('book_id', $article->book_id)->get();

        $book = Book::find($id);
        // $audios = DB::table('audio')
        //     ->where('audio.book_id', $book->id)
        //     ->Join('articles', 'audio.article_id', '=', 'articles.id')
        //     ->groupBy('audio.article_id')
        //     ->orderBy('audio.article_id', 'asc')
        //     ->get();

        $audios = DB::table('articles')
            ->where('articles.book_id', $book->id)
            ->leftJoin('audio', 'articles.id', '=', 'audio.article_id')
            ->groupBy('articles.id')
            ->orderBy('articles.id', 'asc')
            ->get();


        return view('audios.show', compact('audios'));
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

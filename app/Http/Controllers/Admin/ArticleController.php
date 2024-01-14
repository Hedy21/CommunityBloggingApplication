<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Article;
use App\Models\Programming;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Programming::orderBy('id','desc')->paginate(2);
        return view('admin.programming.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tag =  Tag::all();
        $programming = Programming::all();
        return view('admin.article.create',compact('tag','programming'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'description'=>'required',
            'image'=>'required|max:2048|image',

        ]);
        //image upload
        $file = $request->file('image');
        $file_name = uniqid().$file->getClientOriginalName();
        $file->move(public_path('/images'),$file_name);

        //article store
        $createdArticle = Article::create([
            'name'=>$request->name,
            'image'=>$file_name,
            'description' =>$request->description,
            'view_count'=> 0,
            'like_count'=>0
        ]);

        //tag & programming sync
        $article = Article::find($createdArticle->id);
        $article->tag()->sync($request->tag);
        $article->programming()->sync($request->programming);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Programming::where('slug',$id)->first();
        return view('admin.programming.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $updatedSlug = Str::slug($request->name);
        Programming::where('slug',$id)->update([
            'slug' => Str::slug($request->name),
            'name' => $request->name,
        ]);
        return redirect()->route('admin.programming.edit',$updatedSlug)->with('success','Edition Success!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Programming::where('slug',$id)->delete();
        return redirect()->back()->with('success','Deletion Success!');
    }
}

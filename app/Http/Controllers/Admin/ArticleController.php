<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Article;
use App\Models\Programming;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Article::orderBy('id','desc')
        ->with('tag','programming')
        ->paginate(10);
        return view('admin.article.index',compact('data'));
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
            'slug'=>Str::slug($request->name),
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
        return redirect()->back()->with('success','Created');
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
        $data = Article::where('id',$id)->with('tag','programming')->first();
        $tag =  Tag::all();
        $programming = Programming::all();
        return view('admin.article.edit',compact('data','tag','programming'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Article::find($id);
        //image
        if($file = $request->file('image')){
            File::delete(public_path('/images/'.$data->image));
            //image upload
            $file_name = uniqid().$file->getClientOriginalName();
            $file->move(public_path('/images'),$file_name);
        }else{
            //image upload
            $file_name = $data->image;
        }

        //article update
        $data->update([
            'slug'=>Str::slug($request->name),
            'name'=>$request->name,
            'image'=>$file_name,
            'description' =>$request->description,
        ]);

        //tag & programming sync
        $data->tag()->sync($request->tag);
        $data->programming()->sync($request->programming);
        return redirect()->back()->with('success','Updated!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Article::find($id);
        //file delete
        File::delete(public_path('/images/'.$data->image));

        //sync ထားတဲ့ tag နဲ့ programming remove
        $data->tag()->sync([]);
        $data->programming()->sync([]);

        //article delete
        $data->delete();
        return redirect()->back()->with('success','Deleted');
    }
}

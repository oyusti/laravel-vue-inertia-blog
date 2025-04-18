<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->with('user','category')->get();

        return inertia('Post/Index', [
            'posts' => $posts,
            'message' => session('swal'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        
        return inertia('Post/Create',[
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'slug'  => 'required|string|unique:posts',
            'category_id'   =>'required|exists:categories,id'
        ]);

        $data['user_id'] = auth()->id();

        $post = Post::create($data);

        session()->flash('swal', [
            'title' => 'Excelente',
            'text' => 'Post creado satisfactoriamente',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.posts.edit', $post->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {

        $categories = Category::all();

        return inertia('Post/Edit', [
            'post' => $post,
            'categories' => $categories,
            'message' => session('swal'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'slug'  => 'required|string|unique:posts,slug,' . $post->id,
            'category_id'   =>'required|exists:categories,id',
            'extract' => 'nullable',
            'content' => 'nullable',
            'image' => 'nullable|image',
            'is_published' => 'required|boolean'
        ]);

        //Si en el $request viene el campo imagen entonces lo guardamos en el post
        if($request->hasFile('image')){
            
            // Obtener la ruta RELATIVA original almacenada en la BD
            $oldImagePath = $post->getRawOriginal('image_path');

            if($oldImagePath){
                Storage::delete($oldImagePath);
            }

            $data['image_path'] = Storage::put('posts',$request->image);
        }

        $data['user_id'] = auth()->id();
        
        $post->update($data);

        session()->flash('swal', [
            'title' => 'Excelente',
            'text' => 'Post actualizado satisfactoriamente',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.posts.edit', $post);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}

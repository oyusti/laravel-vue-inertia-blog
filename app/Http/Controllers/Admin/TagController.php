<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::latest()->get();

        return inertia('Tag/Index', [
            'tags' => $tags,
            'message' => session('swal'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Tag/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:tags',
        ]);

        Tag::create($request->all());

        session()->flash('swal', [
            'title' => 'Excelente',
            'text' => 'Etiqueta creada satisfactoriamente',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.tags.index')->with((json_encode(session('swal'))));
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return inertia('Tag/Edit',[
            'tag'  => $tag,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:tags,name,' . $tag->id,
        ]);

        $tag->update($request->all());

        session()->flash('swal', [
            'title' => 'Excelente',
            'text' => 'Tag actualizado satisfactoriamente',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.tags.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        session()->flash('swal', [
            'title' => 'Eliminado!',
            'text' => 'Tu registro ha sido eliminado!',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.tags.index');
    }
}

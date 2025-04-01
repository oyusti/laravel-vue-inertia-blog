<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->get();

        return inertia('Category/Index', [
            'categories' => $categories,
            'message' => session('swal'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Category/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
        ]);

        Category::create($request->all());

        session()->flash('swal', [
            'title' => 'Excelente',
            'text' => 'Categoria creada satisfactoriamente',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.categories.index')->with((json_encode(session('swal'))));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {

        return inertia('Category/Edit',[
            'category'  => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        session()->flash('swal', [
            'title' => 'Eliminado!',
            'text' => 'Tu registro ha sido eliminado!',
            'icon' => 'success',
        ]);

        return redirect()->route('admin.categories.index');

        /* return response()->json([
            'message' => [
                'title' => 'Eliminado!',
                'text' => 'Tu registro ha sido eliminado!',
                'icon' => 'success',
            ]
        ]); */
    }
}

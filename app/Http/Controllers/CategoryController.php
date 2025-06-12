<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
{
    // Validação simples
    $validated = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // Cria e salva a categoria
    \App\Models\Category::create($validated);

    // Redireciona para a lista com uma mensagem de sucesso
    return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso!');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
{
    $category = Category::findOrFail($id);
    return view('categories.edit', compact('category'));
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
    $category = Category::findOrFail($id);
    $category->delete();

    return redirect()->route('categories.index')->with('success', 'Categoria excluída com sucesso!');
}

}

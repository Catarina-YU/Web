<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::all();
        return view('authors.index', compact('authors'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Author::create($validated);

        return redirect()->route('authors.index')->with('success', 'Autor criado com sucesso!');
    }

    public function show(string $id)
    {
        $author = Author::findOrFail($id);
        return view('authors.show', compact('author'));
    }

    public function edit(string $id)
    {
        $author = Author::findOrFail($id);
        return view('authors.edit', compact('author'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $author = Author::findOrFail($id);
        $author->update($validated);

        return redirect()->route('authors.index')->with('success', 'Autor atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $author = Author::findOrFail($id);
        $author->delete();

        return redirect()->route('authors.index')->with('success', 'Autor excluído com sucesso!');
    }
}

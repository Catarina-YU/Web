<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::all();
        return view('publishers.index', compact('publishers'));
    }

    public function create()
{
    return view('publishers.create');
}


    // STORE
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255', // <-- adicionado
    ]);

    Publisher::create($validated);

    return redirect()->route('publishers.index')->with('success', 'Editora criada com sucesso!');
}

// UPDATE
public function update(Request $request, string $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255', // <-- adicionado
    ]);

    $publisher = Publisher::findOrFail($id);
    $publisher->update($validated);

    return redirect()->route('publishers.index')->with('success', 'Editora atualizada com sucesso!');
}


    public function show(string $id)
    {
        $publisher = Publisher::findOrFail($id);
        return view('publishers.show', compact('publisher'));
    }

    public function edit(string $id)
    {
        $publisher = Publisher::findOrFail($id);
        return view('publishers.edit', compact('publisher'));
    }

    public function destroy(string $id)
    {
        $publisher = Publisher::findOrFail($id);
        $publisher->delete();

        return redirect()->route('publishers.index')->with('success', 'Editora excluída com sucesso!');
    }
}

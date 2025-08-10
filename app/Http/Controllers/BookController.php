<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;


class BookController extends Controller
{
        public function __construct()
    {
        $this->authorizeResource(Book::class, 'book');
    }
    
    public function index()
    {
        $books = Book::with('author')->paginate(20);
        return view('books.index', compact('books'));
    }

    public function create()
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.create', compact('publishers', 'authors', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'published_year' => 'required',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['url_image'] = $request->file('image')->store('covers/books', 'public');
        }

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    public function createWithId()
    {
        return view('books.create-id');
    }

    public function storeWithId(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'published_year' => 'required',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        Book::create($request->all());

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    public function createWithSelect()
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.create-select', compact('publishers', 'authors', 'categories'));
    }

    public function storeWithSelect(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'published_year' => 'required',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['url_image'] = $request->file('image')->store('covers/books', 'public');
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    public function show(Book $book)
    {
        $book->load(['author', 'publisher', 'category']);
        $users = User::all();

        return view('books.show', compact('book', 'users'));
    }

    public function edit(Book $book)
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.edit', compact('book', 'publishers', 'authors', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'published_year' => 'required',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if($book->url_image && Storage::disk('public')->exists($book->url_image)) {
                Storage::disk('public')->delete($book->url_image);
            }
            $validated['url_image'] = $request->file('image')->store('covers/books', 'public');
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Livro atualizado com sucesso.');
    }

    public function destroy(Book $book)
    {
        if($book->url_image && Storage::disk('public')->exists($book->url_image)) {
            Storage::disk('public')->delete($book->url_image);
        }

        $book->delete();
        return redirect()->route('books.index')->with('success', 'Livro excluído com sucesso.');
    }
}

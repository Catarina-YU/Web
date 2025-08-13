<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Storage;

class BooksController extends Controller
{
    /**
     * Retorna uma lista paginada de livros.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 20);

        $books = Book::with(['author', 'publisher', 'category'])->paginate($limit);

        return response()->json($books);
    }

    /**
     * Cria um novo livro.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'published_year' => 'required|integer',
                'publisher_id' => 'required|exists:publishers,id',
                'author_id' => 'required|exists:authors,id',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $validated['url_image'] = $request->file('image')->store('covers/books', 'public');
            }

            $book = Book::create($validated);

            return response()->json($book, 201); // 201 Created

        } catch (ValidationException $e) {
            return response()->json(['erros' => $e->errors()], 422);
        }
    }

    /**
     * Retorna um livro específico.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Book $book): JsonResponse
    {
        $book->load(['author', 'publisher', 'category']);

        return response()->json($book);
    }

    /**
     * Atualiza um livro existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'published_year' => 'sometimes|required|integer',
                'publisher_id' => 'sometimes|required|exists:publishers,id',
                'author_id' => 'sometimes|required|exists:authors,id',
                'category_id' => 'sometimes|required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            if ($request->hasFile('image')) {
                // Remove a imagem antiga se existir
                if ($book->url_image && Storage::disk('public')->exists($book->url_image)) {
                    Storage::disk('public')->delete($book->url_image);
                }
                $validated['url_image'] = $request->file('image')->store('covers/books', 'public');
            }

            $book->update($validated);

            return response()->json($book);
        } catch (ValidationException $e) {
            return response()->json(['erros' => $e->errors()], 422);
        }
    }

    /**
     * Deleta um livro.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Book $book): JsonResponse
    {
        try {
            // Remove a imagem associada, se existir
            if ($book->url_image && Storage::disk('public')->exists($book->url_image)) {
                Storage::disk('public')->delete($book->url_image);
            }

            $book->delete();

            return response()->json(['mensagem' => 'Livro excluído com sucesso.'], 200);
        } catch (Exception $e) {
            return response()->json(['erro' => 'Não foi possível excluir o livro.'], 500);
        }
    }
}

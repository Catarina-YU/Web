@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Autores</h1>

    <a href="{{ route('authors.create') }}" class="btn btn-primary mb-3">Adicionar Autor</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($authors->isEmpty())
        <p>Nenhum autor cadastrado.</p>
    @else
        <ul class="list-group">
            @foreach($authors as $author)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $author->name }}
                    <div>
                        <a href="{{ route('authors.show', $author->id) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('authors.edit', $author->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('authors.destroy', $author->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Editoras</h1>

    <a href="{{ route('publishers.create') }}" class="btn btn-primary mb-3">Adicionar Editora</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($publishers->isEmpty())
        <p>Nenhuma editora cadastrada.</p>
    @else
        <ul class="list-group">
            @foreach($publishers as $publisher)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $publisher->name }}
                    <div>
                        <a href="{{ route('publishers.show', $publisher->id) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('publishers.edit', $publisher->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('publishers.destroy', $publisher->id) }}" method="POST" style="display:inline;">
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

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Autor</h1>

    <form method="POST" action="{{ route('authors.update', $author->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" name="name" value="{{ $author->name }}" required>
        </div>

        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="{{ route('authors.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection

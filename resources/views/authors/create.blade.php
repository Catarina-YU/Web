@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Novo Autor</h1>

    <form method="POST" action="{{ route('authors.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('authors.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection

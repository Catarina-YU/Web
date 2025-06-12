@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $author->name }}</h1>

    <a href="{{ route('authors.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection

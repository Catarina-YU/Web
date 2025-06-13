@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $publisher->name }}</h1>

    <a href="{{ route('publishers.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection

@extends('adminlte::page')

@section('title', 'Detalhes do Produto')

@section('content_header')
    <div class="container-fluid">
        <h1>Detalhes do Produto</h1>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-eye" style="margin-right: 5px;"></i> Visualizar Produto</h3>
    </div>
    <div class="card-body">
        <p><strong>Nome:</strong> {{ $produto->nome }}</p>
        <p><strong>Descrição:</strong> {{ $produto->descricao }}</p>
        <p><strong>Preço:</strong> R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
        <p><strong>Criado em:</strong> {{ $produto->created_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="card-footer text-right">
        <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('produtos.index') }}" class="btn btn-default">Voltar</a>
    </div>
</div>
@stop

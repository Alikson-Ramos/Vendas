@extends('adminlte::page')

@section('title', 'Detalhes do Cliente')

@section('content_header')
    <div class="container-fluid">
        <h1>Detalhes do Cliente</h1>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-eye" style="margin-right: 5px;"></i> Visualizar Cliente</h3>
    </div>
    <div class="card-body">
        <p><strong>Nome:</strong> {{ $cliente->nome }}</p>
        <p><strong>E-mail:</strong> {{ $cliente->email ?? 'Não informado' }}</p>
        <p><strong>Telefone:</strong> {{ $cliente->telefone ?? 'Não informado' }}</p>
        <p><strong>Criado em:</strong> {{ $cliente->created_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="card-footer text-right">
        <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('clientes.index') }}" class="btn btn-default">Voltar</a>
    </div>
</div>
@stop

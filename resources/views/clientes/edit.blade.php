@extends('adminlte::page')

@section('title', 'Editar Cliente')

@section('content_header')
    <div class="container-fluid">
        <h1>Editar Cliente</h1>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-pencil" style="margin-right: 5px;"></i> Editar Cliente</h3>
    </div>
    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="nome">Nome <span style="color:red;">*</span></label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome', $cliente->nome) }}" required>
                @error('nome')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $cliente->email) }}">
                @error('email')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $cliente->telefone) }}">
                @error('telefone')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('clientes.index') }}" class="btn btn-default">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
</div>
@stop

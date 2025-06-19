@extends('adminlte::page')

@section('title', 'Incluir Cliente')

@section('content_header')
    <div class="container-fluid">
        <h1>Incluir Cliente</h1>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-plus" style="margin-right: 5px;"></i> Novo Cliente</h3>
    </div>
    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="nome">Nome <span style="color:red;">*</span></label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required>
                @error('nome')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                @error('email')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" class="form-control" value="{{ old('telefone') }}">
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

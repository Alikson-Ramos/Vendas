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
            <div class="row">
                {{-- Nome --}}
                <div class="col-md-6 mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-user" style="color:#4e73df; font-size:1.2rem; margin-right:10px;"></i>
                        <label for="nome" class="form-label mb-0" style="font-weight:600; color:#4a4c5a; font-size:0.9rem;">
                            Nome do Cliente <span style="color:#e74a3b;">*</span>
                        </label>
                    </div>
                    <input type="text" name="nome" class="form-control" value="{{ old('nome', $cliente->nome) }}" placeholder="Ex: João Silva" required>
                    @error('nome')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Email --}}
                <div class="col-md-6 mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-envelope" style="color:#4e73df; font-size:1.2rem; margin-right:10px;"></i>
                        <label for="email" class="form-label mb-0" style="font-weight:600; color:#4a4c5a; font-size:0.9rem;">
                            E-mail
                        </label>
                    </div>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $cliente->email) }}" placeholder="cliente@email.com">
                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row">
                {{-- Telefone --}}
                <div class="col-md-6 mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-phone" style="color:#4e73df; font-size:1.2rem; margin-right:10px;"></i>
                        <label for="telefone" class="form-label mb-0" style="font-weight:600; color:#4a4c5a; font-size:0.9rem;">
                            Telefone
                        </label>
                    </div>
                    <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $cliente->telefone) }}" placeholder="(99) 99999-9999">
                    @error('telefone')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('clientes.index') }}" class="btn btn-default">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
</div>
@stop

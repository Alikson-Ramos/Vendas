@extends('adminlte::page')

@section('title', 'Editar Produto')

@section('content_header')
    <div class="container-fluid">
        <h1>Editar Produto</h1>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-pencil" style="margin-right: 5px;"></i> Editar Produto</h3>
    </div>
    <form action="{{ route('produtos.update', $produto->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                {{-- Nome do Produto --}}
                <div class="col-md-8 mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-tag" style="color:#4e73df; font-size:1.2rem; margin-right:10px;"></i>
                        <label for="nome" class="form-label mb-0" style="font-weight:600; color:#4a4c5a; font-size:0.9rem;">
                            Nome do Produto <span style="color:#e74a3b;">*</span>
                        </label>
                    </div>
                    <input type="text" name="nome" class="form-control" value="{{ old('nome', $produto->nome) }}" placeholder="Ex: Smartphone Galaxy S23" required>
                    @error('nome')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Preço --}}
                <div class="col-md-4 mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-dollar-sign" style="color:#4e73df; font-size:1.2rem; margin-right:10px;"></i>
                        <label for="preco" class="form-label mb-0" style="font-weight:600; color:#4a4c5a; font-size:0.9rem;">
                            Preço <span style="color:#e74a3b;">*</span>
                        </label>
                    </div>
                    <div style="position:relative;">
                        <span class="currency-symbol" style="position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#858796;font-weight:bold;">R$</span>
                        <input type="text" name="preco" class="form-control price-input" style="padding-left:35px;" value="{{ old('preco', number_format($produto->preco, 2, ',', '')) }}" placeholder="0,00" required>
                    </div>
                    @error('preco')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            {{-- Descrição --}}
            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-file-alt" style="color:#4e73df; font-size:1.2rem; margin-right:10px;"></i>
                    <label for="descricao" class="form-label mb-0" style="font-weight:600; color:#4a4c5a; font-size:0.9rem;">
                        Detalhes do Produto
                    </label>
                </div>
                <textarea name="descricao" class="form-control" rows="4" maxlength="500" placeholder="Descreva as características, especificações e benefícios do produto...">{{ old('descricao', $produto->descricao) }}</textarea>
                <div style="font-size:0.8rem;color:#858796;text-align:right;">Máximo 500 caracteres</div>
                @error('descricao')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('produtos.index') }}" class="btn btn-default">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
</div>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let input = document.querySelector('.price-input');
            if(input){
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = (value / 100).toFixed(2) + '';
                    value = value.replace('.', ',');
                    value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
                    e.target.value = value;
                });
            }
        });
    </script>
@stop

@extends('adminlte::page')

@section('title', 'Detalhes da Venda')

@section('content_header')
    <h1>Detalhes da Venda #{{ $venda->id }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <span><b>Venda:</b> #{{ $venda->id }}</span>
        <a href="{{ route('vendas.edit', $venda->id) }}" class="btn btn-sm btn-info float-right">
            <i class="fa fa-pencil"></i> Editar
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-md-6">
                <strong>Cliente:</strong>
                {{ $venda->cliente->nome ?? '-' }}
            </div>
            <div class="col-md-6">
                <strong>Vendedor:</strong>
                {{ $venda->user->name ?? '-' }}
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6">
                <strong>Data:</strong>
                {{ $venda->created_at->format('d/m/Y H:i') }}
            </div>
            <div class="col-md-6">
                <strong>Forma de Pagamento:</strong>
                {{ $venda->forma_pagamento }}
            </div>
        </div>

        <hr>
        <h5>Itens da Venda</h5>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th style="width:80px;">Qtd</th>
                    <th style="width:120px;">Preço Unitário</th>
                    <th style="width:120px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($venda->itens as $item)
                    <tr>
                        <td>{{ $item->produto->nome ?? '-' }}</td>
                        <td>{{ $item->quantidade }}</td>
                        <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($item->preco_total, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><b>Total da Venda:</b></td>
                    <td><b>R$ {{ number_format($venda->total, 2, ',', '.') }}</b></td>
                </tr>
            </tfoot>
        </table>

        @if($venda->forma_pagamento == 'Parcelado' && $venda->parcelas->count())
            <hr>
            <h5>Parcelas</h5>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venda->parcelas as $parcela)
                        <tr>
                            <td>{{ $parcela->numero }}</td>
                            <td>{{ \Carbon\Carbon::parse($parcela->data_vencimento)->format('d/m/Y') }}</td>
                            <td>R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                            <td>
                                @if($parcela->paga)
                                    <span class="badge badge-success">Paga</span>
                                @else
                                    <span class="badge badge-warning">Em aberto</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="card-footer">
        <a href="{{ route('vendas.index') }}" class="btn btn-default">Voltar</a>
    </div>
</div>
@stop

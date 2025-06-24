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

        {{-- cabeçalho com resumo --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <strong>Cliente:</strong> {{ $venda->cliente->nome ?? '-' }}
            </div>
            <div class="col-md-4">
                <strong>Vendedor:</strong> {{ $venda->user->name ?? '-' }}
            </div>
            <div class="col-md-4">
                <strong>Data:</strong> {{ $venda->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-4">
                <strong>Forma de Pagamento:</strong> {{ $venda->forma_pagamento }}
            </div>
            @if(in_array($venda->forma_pagamento, ['Parcelado','Personalizado']) && $venda->parcelas->count())
                <div class="col-md-4">
                    <strong>Qtd. Parcelas:</strong> {{ $venda->parcelas->count() }}
                </div>
                <div class="col-md-4">
                    <strong>Valor Total Parcelas:</strong>
                    R$ {{ number_format($venda->parcelas->sum('valor'), 2, ',', '.') }}
                </div>
            @endif
        </div>

        <hr>

        <h5>Itens da Venda</h5>
        <table class="table table-bordered table-sm mb-4">
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

        @if(in_array($venda->forma_pagamento, ['Parcelado','Personalizado']) && $venda->parcelas->count())
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
                                @if(!empty($parcela->paga) && $parcela->paga)
                                    <span class="badge badge-success">Paga</span>
                                @else
                                    <span class="badge badge-warning">Em aberto</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right"><b>Total das Parcelas:</b></td>
                        <td colspan="2">
                            <b>R$ {{ number_format($venda->parcelas->sum('valor'), 2, ',', '.') }}</b>
                        </td>
                    </tr>
                </tfoot>
            </table>
        @endif

    </div>
    <div class="card-footer">
        <a href="{{ route('vendas.index') }}" class="btn btn-default">Voltar</a>
    </div>
</div>
@stop

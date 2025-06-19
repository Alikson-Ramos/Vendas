@extends('adminlte::page')

@section('title', 'Editar Venda')

@section('content_header')
    <h1>Editar Venda #{{ $venda->id }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <b>Venda #{{ $venda->id }}</b>
    </div>
    <form action="{{ route('vendas.update', $venda->id) }}" method="POST" id="form-venda">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="cliente_id" class="form-label">
                        <i class="fas fa-user" style="color:#4e73df;"></i> Cliente (opcional)
                    </label>
                    <select name="cliente_id" class="form-control">
                        <option value="">-- Selecione --</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}" @if(old('cliente_id', $venda->cliente_id) == $cliente->id) selected @endif>{{ $cliente->nome }}</option>
                        @endforeach
                    </select>
                    @error('cliente_id')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="forma_pagamento" class="form-label">
                        <i class="fas fa-credit-card" style="color:#4e73df;"></i> Forma de Pagamento
                        <span style="color:#e74a3b;">*</span>
                    </label>
                    <select name="forma_pagamento" class="form-control" id="forma_pagamento" required>
                        <option value="Dinheiro" @if(old('forma_pagamento', $venda->forma_pagamento) == 'Dinheiro') selected @endif>Dinheiro</option>
                        <option value="Cartão" @if(old('forma_pagamento', $venda->forma_pagamento) == 'Cartão') selected @endif>Cartão</option>
                        <option value="Parcelado" @if(old('forma_pagamento', $venda->forma_pagamento) == 'Parcelado') selected @endif>Parcelado</option>
                    </select>
                </div>
            </div>
            <hr>
            <h5 class="mb-3"><i class="fas fa-box" style="color:#4e73df;"></i> Itens da Venda</h5>
            <table class="table table-bordered" id="tabela-itens">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th style="width:120px;">Qtd</th>
                        <th style="width:180px;">Preço Unitário</th>
                        <th style="width:180px;">Total</th>
                        <th style="width:40px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venda->itens as $idx => $item)
                        <tr class="item-row">
                            <td>
                                <select name="itens[{{ $idx }}][produto_id]" class="form-control produto-select" required>
                                    <option value="">Selecione</option>
                                    @foreach ($produtos as $produto)
                                        <option value="{{ $produto->id }}" @if($item->produto_id == $produto->id) selected @endif>{{ $produto->nome }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" min="1" name="itens[{{ $idx }}][quantidade]" class="form-control quantidade" value="{{ $item->quantidade }}" required></td>
                            <td><input type="text" name="itens[{{ $idx }}][preco_unitario]" class="form-control preco-unitario" value="{{ number_format($item->preco_unitario, 2, ',', '.') }}" required></td>
                            <td><input type="text" class="form-control total-item" value="{{ number_format($item->preco_total, 2, ',', '.') }}" readonly></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger remover-item" title="Remover">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="btn btn-sm btn-success mb-3" id="adicionar-item">
                <i class="fa fa-plus"></i> Adicionar Item
            </button>

            <div class="row">
                <div class="col-md-6">
                    <strong>Total da Venda:</strong>
                </div>
                <div class="col-md-6 text-right">
                    <input type="text" name="total" id="total-venda" class="form-control text-right" value="{{ number_format($venda->total, 2, ',', '.') }}" readonly>
                </div>
            </div>

            <div id="parcelamento" style="display:{{ $venda->forma_pagamento == 'Parcelado' ? 'block' : 'none' }}; margin-top:30px;">
                <hr>
                <h5><i class="fas fa-file-invoice-dollar" style="color:#4e73df;"></i> Parcelamento</h5>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label for="qtd_parcelas" class="form-label">Qtd. de Parcelas</label>
                        <select class="form-control" id="qtd_parcelas">
                            @for($i=2; $i<=12; $i++)
                                <option value="{{$i}}" @if(isset($venda->parcelas[0]) && count($venda->parcelas) == $i) selected @endif>{{$i}}x</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <table class="table table-sm" id="tabela-parcelas">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Data Vencimento</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($venda->forma_pagamento == 'Parcelado')
                            @foreach ($venda->parcelas as $idx => $parcela)
                                <tr>
                                    <td>{{ $parcela->numero }}
                                        <input type="hidden" name="parcelas[{{ $idx }}][numero]" value="{{ $parcela->numero }}">
                                    </td>
                                    <td><input type="date" name="parcelas[{{ $idx }}][data_vencimento]" class="form-control" value="{{ $parcela->data_vencimento }}" required></td>
                                    <td><input type="text" name="parcelas[{{ $idx }}][valor]" class="form-control" value="{{ number_format($parcela->valor, 2, ',', '.') }}" required></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('vendas.show', $venda->id) }}" class="btn btn-default">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
    var precosProdutos = {
        @foreach($produtos as $produto)
            "{{ $produto->id }}": "{{ number_format($produto->preco, 2, ',', '') }}",
        @endforeach
    };

    $(document).on('change', '.produto-select', function(){
        let idProduto = $(this).val();
        let preco = precosProdutos[idProduto] || "0,00";
        $(this).closest('tr').find('.preco-unitario').val(preco).trigger('input');
    });

    function atualizarTotais() {
        let totalVenda = 0;
        $('#tabela-itens tbody tr').each(function(){
            let quantidade = parseInt($(this).find('.quantidade').val()) || 0;
            let precoUnitario = ($(this).find('.preco-unitario').val() || '0').replace(/\./g,'').replace(',','.');
            precoUnitario = parseFloat(precoUnitario) || 0;
            let totalItem = quantidade * precoUnitario;
            $(this).find('.total-item').val(totalItem.toFixed(2).replace('.',','));
            totalVenda += totalItem;
        });
        $('#total-venda').val(totalVenda.toFixed(2).replace('.',','));
        if($('#forma_pagamento').val() === 'Parcelado') gerarParcelas();
    }

    $(document).on('input change', '.quantidade, .preco-unitario', function(){
        atualizarTotais();
    });

    $('#adicionar-item').click(function(){
        let idx = $('#tabela-itens tbody tr').length;
        let row = `
        <tr class="item-row">
            <td>
                <select name="itens[${idx}][produto_id]" class="form-control produto-select" required>
                    <option value="">Selecione</option>
                    @foreach ($produtos as $produto)
                        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" min="1" name="itens[${idx}][quantidade]" class="form-control quantidade" value="1" required></td>
            <td><input type="text" name="itens[${idx}][preco_unitario]" class="form-control preco-unitario" value="0,00" required></td>
            <td><input type="text" class="form-control total-item" value="0,00" readonly></td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remover-item" title="Remover">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
        `;
        $('#tabela-itens tbody').append(row);
    });

    $(document).on('click', '.remover-item', function(){
        $(this).closest('tr').remove();
        atualizarTotais();
    });

    $('#forma_pagamento').change(function(){
        if($(this).val() === 'Parcelado') {
            $('#parcelamento').show();
            gerarParcelas();
        } else {
            $('#parcelamento').hide();
        }
    });

    $('#qtd_parcelas').change(function(){
        gerarParcelas();
    });

    function gerarParcelas() {
        let total = ($('#total-venda').val() || '0').replace(/\./g,'').replace(',','.');
        total = parseFloat(total) || 0;
        let qtd = parseInt($('#qtd_parcelas').val()) || 2;
        let valorParcela = total / qtd;
        let hoje = new Date();
        let tbody = '';
        for(let i=1; i<=qtd; i++) {
            let venc = new Date(hoje.getFullYear(), hoje.getMonth()+i, hoje.getDate());
            let vencStr = venc.toISOString().slice(0,10);
            tbody += `<tr>
                <td>${i}<input type="hidden" name="parcelas[${i-1}][numero]" value="${i}"></td>
                <td><input type="date" name="parcelas[${i-1}][data_vencimento]" class="form-control" value="${vencStr}" required></td>
                <td><input type="text" name="parcelas[${i-1}][valor]" class="form-control" value="${valorParcela.toFixed(2).replace('.',',')}" required></td>
            </tr>`;
        }
        $('#tabela-parcelas tbody').html(tbody);
    }

    $(document).on('input', '.preco-unitario', function(){
        let v = $(this).val().replace(/\D/g, '');
        v = (v/100).toFixed(2)+'';
        v = v.replace('.', ',');
        v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        $(this).val(v);
        atualizarTotais();
    });

    $(document).ready(function(){
        atualizarTotais();
    });

</script>
@stop

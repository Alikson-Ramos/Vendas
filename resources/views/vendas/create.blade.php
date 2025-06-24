@extends('adminlte::page')

@section('title', 'Nova Venda')

@section('content_header')
<div class="container-fluid">
    <h1>Nova Venda</h1>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-cash-register mr-2"></i>Nova Venda</h3>
    </div>
    <form action="{{ route('vendas.store') }}" method="POST" id="form-venda">
        @csrf
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-user text-primary"></i> Cliente (opcional)</label>
                    <select name="cliente_id" class="form-control">
                        <option value="">-- Selecione --</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id')==$cliente->id?'selected':'' }}>{{ $cliente->nome }}</option>
                        @endforeach
                    </select>
                    @error('cliente_id')<span class="text-danger small">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-credit-card text-primary"></i> Forma de Pagamento<span class="text-danger">*</span></label>
                    <select name="forma_pagamento" id="forma_pagamento" class="form-control" required>
                        <option>Dinheiro</option>
                        <option>Cartão</option>
                        <option>Parcelado</option>
                        <option>Personalizado</option>
                    </select>
                </div>
            </div>
            <hr>
            <h5 class="mb-3"><i class="fas fa-box text-primary"></i> Itens da Venda</h5>
            <table class="table table-bordered" id="tabela-itens">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th style="width:120px;">Qtd</th>
                        <th style="width:180px;">Preço Unit.</th>
                        <th style="width:180px;">Total</th>
                        <th style="width:40px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="item-row">
                        <td>
                            <select name="itens[0][produto_id]" class="form-control produto-select" required>
                                <option value="">Selecione</option>
                                @foreach($produtos as $produto)
                                    <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="itens[0][quantidade]" class="form-control quantidade" min="1" value="1" required></td>
                        <td><input type="text" name="itens[0][preco_unitario]" class="form-control preco-unitario" value="0,00" required></td>
                        <td><input type="text" class="form-control total-item" value="0,00" readonly></td>
                        <td><button type="button" class="btn btn-sm btn-danger remover-item"><i class="fa fa-trash"></i></button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-success btn-sm mb-3" id="adicionar-item"><i class="fa fa-plus"></i> Adicionar Item</button>
            <div class="row mb-4">
                <div class="col-md-6"><strong>Total da Venda:</strong></div>
                <div class="col-md-6"><input type="text" id="total-venda" name="total" class="form-control text-right" value="0,00" readonly></div>
            </div>

            <div id="parcelamento" style="display:none; margin-top:30px;">
                <hr>
                <h5><i class="fas fa-file-invoice-dollar text-primary"></i> Parcelamento</h5>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label class="form-label">Qtd. de Parcelas</label>
                        <select id="qtd_parcelas" class="form-control">
                            @for($i=2;$i<=12;$i++)<option value="{{$i}}">{{$i}}x</option>@endfor
                        </select>
                    </div>
                </div>
                <table class="table table-sm" id="tabela-parcelas">
                    <thead><tr><th>#</th><th>Vencimento</th><th>Valor</th></tr></thead>
                    <tbody></tbody>
                </table>
            </div>

            <div id="personalizado" style="display:none; margin-top:30px;">
                <hr>
                <h5><i class="fas fa-file-invoice-dollar text-primary"></i> Parcelamento Personalizado</h5>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label class="form-label">Qtd. de Parcelas</label>
                        <select id="qtd_parcelas_personalizado" class="form-control">
                            @for($i=2;$i<=12;$i++)<option value="{{$i}}">{{$i}}x</option>@endfor
                        </select>
                    </div>
                </div>
                <table class="table table-bordered" id="tabela-personalizado">
                    <thead><tr><th>#</th><th>Vencimento</th><th>Valor</th></tr></thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr><th colspan="2" class="text-right">Total das Parcelas</th><th id="total-parcelas-custom">R$ 0,00</th></tr>
                        <tr><th colspan="2" class="text-right">Falta para fechar</th><th id="falta-fechar-custom">R$ 0,00</th></tr>
                    </tfoot>
                </table>
                <div id="alerta-personalizado" class="alert alert-danger" style="display:none;"></div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('vendas.index') }}" class="btn btn-default">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar Venda</button>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
var precos = {@foreach($produtos as $p)"{{$p->id}}":"{{number_format($p->preco,2,',','')}}",@endforeach};

function atualizarTotais(){
    let total=0;
    $('#tabela-itens tbody tr').each(function(){
        let q=+$(this).find('.quantidade').val()||0,
            pu=parseFloat($(this).find('.preco-unitario').val().replace(/\./g,'').replace(',','.'))||0,
            ti=q*pu;
        $(this).find('.total-item').val(ti.toFixed(2).replace('.',','));
        total+=ti;
    });
    $('#total-venda').val(total.toFixed(2).replace('.',','));
    if($('#forma_pagamento').val()==='Parcelado') gerarParcelas();
    if($('#forma_pagamento').val()==='Personalizado') gerarParcelasPersonalizado();
}

$(document).on('change','.produto-select',function(){
    let v=precos[$(this).val()]||'0,00';
    $(this).closest('tr').find('.preco-unitario').val(v).trigger('input');
});

$(document).on('input change','.quantidade,.preco-unitario',atualizarTotais);

$('#adicionar-item').click(function(){
    let idx=$('#tabela-itens tbody tr').length,
        opcs=`@foreach($produtos as $p)<option value="{{$p->id}}">{{$p->nome}}</option>@endforeach`,
        row=`<tr class="item-row"><td><select name="itens[${idx}][produto_id]" class="form-control produto-select" required><option value="">Selecione</option>${opcs}</select></td>
             <td><input type="number" name="itens[${idx}][quantidade]" class="form-control quantidade" min="1" value="1" required></td>
             <td><input type="text" name="itens[${idx}][preco_unitario]" class="form-control preco-unitario" value="0,00" required></td>
             <td><input type="text" class="form-control total-item" value="0,00" readonly></td>
             <td><button type="button" class="btn btn-sm btn-danger remover-item"><i class="fa fa-trash"></i></button></td></tr>`;
    $('#tabela-itens tbody').append(row);
});

$(document).on('click','.remover-item',function(){
    $(this).closest('tr').remove();
    atualizarTotais();
});

$('#forma_pagamento').change(function(){
    let v=$(this).val();
    $('#parcelamento,#personalizado').hide();
    if(v==='Parcelado'){ $('#parcelamento').show(); gerarParcelas(); }
    if(v==='Personalizado'){ $('#personalizado').show(); gerarParcelasPersonalizado(); }
});

$('#qtd_parcelas').change(gerarParcelas);

function gerarParcelas(){
    let qtd=+$('#qtd_parcelas').val()||2,
        total=parseFloat($('#total-venda').val().replace(/\./g,'').replace(',','.'))||0,
        base=Math.floor((total/qtd)*100)/100,
        resto=Math.round((total-base*qtd)*100)/100,
        hoje=new Date(), s='';
    for(let i=0;i<qtd;i++){
        let v=base+(i===qtd-1?resto:0),
            dt=new Date(hoje.getFullYear(),hoje.getMonth()+i+1,hoje.getDate()).toISOString().slice(0,10);
        s+=`<tr>
            <td>${i+1}<input type="hidden" name="parcelas[${i}][numero]" value="${i+1}"></td>
            <td><input type="date" name="parcelas[${i}][data_vencimento]" class="form-control" value="${dt}" required></td>
            <td><input type="text" name="parcelas[${i}][valor]" class="form-control" value="${v.toFixed(2).replace('.',',')}" required></td>
        </tr>`;
    }
    $('#tabela-parcelas tbody').html(s);
}

$('#qtd_parcelas_personalizado').change(gerarParcelasPersonalizado);

function gerarParcelasPersonalizado(){
    let qtd=+$('#qtd_parcelas_personalizado').val()||2,
        total=parseFloat($('#total-venda').val().replace(/\./g,'').replace(',','.'))||0,
        base=Math.floor((total/qtd)*100)/100,
        resto=Math.round((total-base*qtd)*100)/100,
        hoje=new Date(), s='';
    for(let i=0;i<qtd;i++){
        let v=base+(i===qtd-1?resto:0),
            dt=new Date(hoje.getFullYear(),hoje.getMonth()+i+1,hoje.getDate()).toISOString().slice(0,10);
        s+=`<tr>
            <td>${i+1}<input type="hidden" name="parcelas_personalizadas[${i}][numero]" value="${i+1}"></td>
            <td><input type="date" name="parcelas_personalizadas[${i}][data_vencimento]" class="form-control data-parcela-personalizada" value="${dt}" required></td>
            <td><input type="text" name="parcelas_personalizadas[${i}][valor]" class="form-control valor-parcela-personalizada" data-idx="${i}" value="${v.toFixed(2).replace('.',',')}" required></td>
        </tr>`;
    }
    $('#tabela-personalizado tbody').html(s);
    atualizarTabelaPersonalizada();
}

$(document).on('input','.valor-parcela-personalizada',function(){
    let idx=+$(this).data('idx'),
        qtd=$('.valor-parcela-personalizada').length,
        total=parseFloat($('#total-venda').val().replace(/\./g,'').replace(',','.'))||0,
        vals=[], somaAnt=0;
    $('.valor-parcela-personalizada').each(function(i){
        let x=parseFloat($(this).val().replace(/\./g,'').replace(',','.'))||0;
        vals[i]=x;
        if(i<idx) somaAnt+=x;
    });
    let cur=parseFloat($(this).val().replace(/\./g,'').replace(',','.'))||0,
        saldo=total-(somaAnt+cur),
        rest=qtd-idx-1;
    if(rest>0 && saldo>0){
        let b=Math.floor((saldo/rest)*100)/100,
            d=saldo-(b*rest);
        for(let i=idx+1;i<qtd;i++){
            let nv=(i===qtd-1?b+d:b);
            vals[i]=nv;
            $(`.valor-parcela-personalizada[data-idx="${i}"]`).val(nv.toFixed(2).replace('.',','));
        }
    }
    atualizarTabelaPersonalizada();
});

function atualizarTabelaPersonalizada(){
    let soma=0,total=parseFloat($('#total-venda').val().replace(/\./g,'').replace(',','.'))||0;
    $('.valor-parcela-personalizada').each(function(){
        soma+=parseFloat($(this).val().replace(/\./g,'').replace(',','.'))||0;
    });
    let falta=total-soma;
    $('#total-parcelas-custom').text('R$ '+soma.toFixed(2).replace('.',','));
    $('#falta-fechar-custom').text('R$ '+falta.toFixed(2).replace('.',','));
    if(Math.abs(falta)>0.01) $('#alerta-personalizado').show().text('Soma das parcelas não fecha!');
    else $('#alerta-personalizado').hide();
}

$('#form-venda').submit(function(e){
    if($('#forma_pagamento').val()==='Personalizado'){
        let total=parseFloat($('#total-venda').val().replace(/\./g,'').replace(',','.'))||0, soma=0;
        $('.valor-parcela-personalizada').each(function(){ soma+=parseFloat($(this).val().replace(/\./g,'').replace(',','.'))||0 });
        if(Math.abs(total-soma)>0.01){ e.preventDefault(); $('#alerta-personalizado').show().text('Soma das parcelas não fecha!'); }
    }
});

$(document).ready(function(){
    atualizarTotais();
    if($('#forma_pagamento').val()==='Parcelado'){ $('#parcelamento').show(); gerarParcelas(); }
    if($('#forma_pagamento').val()==='Personalizado'){ $('#personalizado').show(); gerarParcelasPersonalizado(); }
});
</script>
@stop

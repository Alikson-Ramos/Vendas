@extends('adminlte::page')

@section('title', 'Editar Venda')

@section('content_header')
<div class="container-fluid">
    <h1>Editar Venda #{{ $venda->id }}</h1>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-cash-register mr-2"></i>Editar Venda #{{ $venda->id }}</h3>
    </div>
    <form action="{{ route('vendas.update', $venda->id) }}" method="POST" id="form-venda">
        @csrf @method('PUT')
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-user text-primary"></i> Cliente (opcional)</label>
                    <select name="cliente_id" class="form-control">
                        <option value="">-- Selecione --</option>
                        @foreach($clientes as $c)
                            <option value="{{ $c->id }}" {{ old('cliente_id',$venda->cliente_id)==$c->id?'selected':'' }}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                    @error('cliente_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-credit-card text-primary"></i> Forma de Pagamento<span class="text-danger">*</span></label>
                    <select name="forma_pagamento" id="forma_pagamento" class="form-control" required>
                        @foreach(['Dinheiro','Cartão','Parcelado','Personalizado'] as $fp)
                            <option {{ old('forma_pagamento',$venda->forma_pagamento)==$fp?'selected':'' }}>{{ $fp }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>

            <h5 class="mb-3"><i class="fas fa-box text-primary"></i> Itens da Venda</h5>
            <table class="table table-bordered" id="tabela-itens">
                <thead>
                    <tr>
                        <th>Produto</th><th style="width:120px">Qtd</th><th style="width:180px">Preço Unit.</th><th style="width:180px">Total</th><th style="width:40px"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($venda->itens as $i=>$item)
                    <tr class="item-row">
                        <td>
                            <select name="itens[{{$i}}][produto_id]" class="form-control produto-select" required>
                                <option value="">Selecione</option>
                                @foreach($produtos as $p)
                                    <option value="{{$p->id}}" {{$item->produto_id==$p->id?'selected':''}}>{{$p->nome}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="itens[{{$i}}][quantidade]" class="form-control quantidade" min="1" value="{{$item->quantidade}}" required></td>
                        <td><input type="text" name="itens[{{$i}}][preco_unitario]" class="form-control preco-unitario" value="{{number_format($item->preco_unitario,2,',','.')}}" required></td>
                        <td><input type="text" class="form-control total-item" value="{{number_format($item->preco_total,2,',','.')}}" readonly></td>
                        <td><button type="button" class="btn btn-sm btn-danger remover-item"><i class="fa fa-trash"></i></button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button type="button" class="btn btn-sm btn-success mb-3" id="adicionar-item"><i class="fa fa-plus"></i> Adicionar Item</button>

            <div class="row mb-4">
                <div class="col-md-6"><strong>Total da Venda:</strong></div>
                <div class="col-md-6"><input type="text" id="total-venda" name="total" class="form-control text-right" value="{{ number_format($venda->total,2,',','.') }}" readonly></div>
            </div>

            <div id="parcelamento" style="display:{{ $venda->forma_pagamento=='Parcelado'?'block':'none' }};margin-top:30px">
                <hr>
                <h5><i class="fas fa-file-invoice-dollar text-primary"></i> Parcelamento</h5>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label class="form-label">Qtd. Parcelas</label>
                        <select id="qtd_parcelas" class="form-control">
                            @for($i=2;$i<=12;$i++)
                                <option value="{{$i}}" {{ $venda->parcelas->count()==$i?'selected':'' }}>{{$i}}x</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <table class="table table-sm" id="tabela-parcelas">
                    <thead><tr><th>#</th><th>Vencimento</th><th>Valor</th></tr></thead>
                    <tbody></tbody>
                </table>
            </div>

            <div id="personalizado" style="display:{{ $venda->forma_pagamento=='Personalizado'?'block':'none' }};margin-top:30px">
                <hr>
                <h5><i class="fas fa-file-invoice-dollar text-primary"></i> Parcelamento Personalizado</h5>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label class="form-label">Qtd. Parcelas</label>
                        <select id="qtd_parcelas_personalizado" class="form-control">
                            @for($i=2;$i<=12;$i++)
                                <option value="{{$i}}" {{ $venda->parcelas->count()==$i?'selected':'' }}>{{$i}}x</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <table class="table table-bordered" id="tabela-personalizado">
                    <thead><tr><th>#</th><th>Vencimento</th><th>Valor</th></tr></thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr><th colspan="2" class="text-right">Total Parcelas</th><th id="total-parcelas-custom">R$ 0,00</th></tr>
                        <tr><th colspan="2" class="text-right">Falta fechar</th><th id="falta-fechar-custom">R$ 0,00</th></tr>
                    </tfoot>
                </table>
                <div id="alerta-personalizado" class="alert alert-danger d-none"></div>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('vendas.show',$venda->id) }}" class="btn btn-default">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
let precos = {@foreach($produtos as $p)"{{$p->id}}":"{{number_format($p->preco,2,',','')}}",@endforeach},
    custom = [];

@if($venda->forma_pagamento=='Personalizado')
    @foreach($venda->parcelas as $i => $p)
        custom[{{$i}}] = {valor:"{{number_format($p->valor,2,',','.')}}",data:"{{$p->data_vencimento}}"};
    @endforeach
@endif

function atualizarTotais(){
    let t=0;
    $('#tabela-itens tr').each(function(){
        let q=+$(this).find('.quantidade').val()||0,
            pu=parseFloat($(this).find('.preco-unitario').val().replace(/\./g,'').replace(',','.'))||0,
            ti=q*pu;
        $(this).find('.total-item').val(ti.toFixed(2).replace('.',','));
        t+=ti;
    });
    $('#total-venda').val(t.toFixed(2).replace('.',','));
    if($('#forma_pagamento').val()==='Parcelado') gerarParcelas();
    if($('#forma_pagamento').val()==='Personalizado') gerarPersonalizado();
}

$(document).on('change','.produto-select',function(){
    $(this).closest('tr').find('.preco-unitario').val(precos[$(this).val()]||'0,00').trigger('input');
});

$(document).on('input change','.quantidade,.preco-unitario',atualizarTotais);

$('#adicionar-item').click(function(){
    let i=$('#tabela-itens tr').length, o='';
    @foreach($produtos as $p) o+=`<option value="{{$p->id}}">{{$p->nome}}</option>`; @endforeach
    $('#tabela-itens tbody').append(`
        <tr>
            <td><select name="itens[${i}][produto_id]" class="form-control produto-select">${o}</select></td>
            <td><input type="number" name="itens[${i}][quantidade]" class="form-control quantidade" min="1" value="1"></td>
            <td><input type="text" name="itens[${i}][preco_unitario]" class="form-control preco-unitario" value="0,00"></td>
            <td><input readonly class="form-control total-item" value="0,00"></td>
            <td><button type="button" class="btn btn-sm btn-danger remover-item"><i class="fa fa-trash"></i></button></td>
        </tr>`);
});

$(document).on('click','.remover-item',function(){ $(this).closest('tr').remove(); atualizarTotais(); });

$('#forma_pagamento').change(function(){
    let v=$(this).val();
    $('#parcelamento,#personalizado').hide().find('tbody').empty();
    if(v==='Parcelado'){ $('#parcelamento').show(); gerarParcelas(); }
    if(v==='Personalizado'){ $('#personalizado').show(); gerarPersonalizado(); }
});

function gerarParcelas() {
    let qtd = +$('#qtd_parcelas').val();
    let total = parseFloat($('#total-venda').val().replace(/\./g, '').replace(',', '.')) || 0;
    let valorBase = Math.floor((total / qtd) * 100) / 100;
    let resto = Math.round((total - valorBase * qtd) * 100) / 100;
    let hoje = new Date();
    let html = '';
    for (let i = 0; i < qtd; i++) {
        let valorParcela = valorBase;
        if (i === qtd - 1) valorParcela += resto;
        let dataVencimento = new Date(hoje);
        dataVencimento.setMonth(hoje.getMonth() + i + 1);
        let dataStr = dataVencimento.toISOString().split('T')[0];
        html += `<tr>
            <td>${i+1}<input type="hidden" name="parcelas[${i}][numero]" value="${i+1}"></td>
            <td><input type="date" name="parcelas[${i}][data_vencimento]" class="form-control" value="${dataStr}"></td>
            <td><input type="text" name="parcelas[${i}][valor]" class="form-control" value="${valorParcela.toFixed(2).replace('.', ',')}"></td>
        </tr>`;
    }
    $('#tabela-parcelas tbody').html(html);
}

$('#qtd_parcelas').change(gerarParcelas);

function gerarPersonalizado(){
    let qtd=+$('#qtd_parcelas_personalizado').val(), total=parseFloat($('#total-venda').val().replace(/\./g,'').replace(',','.'))||0,
        hoje=new Date(), s='';
    for(let i=0;i<qtd;i++){
        let v=custom[i]?parseFloat(custom[i].valor.replace(/\./g,'').replace(',','.')):Math.floor((total/qtd)*100)/100,
            d=custom[i]?custom[i].data:new Date(hoje.getFullYear(),hoje.getMonth()+i+1,hoje.getDate()).toISOString().slice(0,10);
        if(!custom[i]){ 
            let resto=Math.round((total-Math.floor((total/qtd)*100)/100*qtd)*100)/100;
            if(i===qtd-1) v+=(resto);
        }
        s+=`<tr><td>${i+1}<input type="hidden" name="parcelas_personalizadas[${i}][numero]" value="${i+1}"></td>
            <td><input type="date" name="parcelas_personalizadas[${i}][data_vencimento]" class="form-control" value="${d}"></td>
            <td><input type="text" data-idx="${i}" name="parcelas_personalizadas[${i}][valor]" class="form-control valor-parcela-personalizada" value="${v.toFixed(2).replace('.',',')}"></td></tr>`;
    }
    $('#tabela-personalizado tbody').html(s);
    atualizarPersonalizado();
}

$('#qtd_parcelas_personalizado').change(gerarPersonalizado);

$(document).on('input','.valor-parcela-personalizada',function(){
    let idx=+$(this).data('idx');
    let total=parseFloat($('#total-venda').val().replace(/\./g,'').replace(',','.'))||0;
    let vals=$('.valor-parcela-personalizada').map((i,el)=>parseFloat($(el).val().replace(/\./g,'').replace(',','.'))||0).get();
    let somaAteIdx = vals.slice(0, idx + 1).reduce((a, b) => a + b, 0);
    let rest = vals.length - idx - 1;
    let saldo = total - somaAteIdx;
    if (rest > 0) {
        let b = Math.floor((saldo / rest) * 100) / 100;
        let d = saldo - (b * rest);
        for (let i = idx + 1; i < vals.length; i++) {
            vals[i] = b + ((i === vals.length - 1) ? d : 0);
            $(`.valor-parcela-personalizada[data-idx="${i}"]`).val(vals[i].toFixed(2).replace('.',','));
        }
    }
    atualizarPersonalizado();
});

function atualizarPersonalizado(){
    let total=parseFloat($('#total-venda').val().replace(/\./g,'').replace(',','.'))||0,
        soma= $('.valor-parcela-personalizada').toArray().reduce((a,el)=>a + (parseFloat($(el).val().replace(/\./g,'').replace(',','.'))||0),0),
        falta=total-soma;
    $('#total-parcelas-custom').text('R$ '+soma.toFixed(2).replace('.',','));
    $('#falta-fechar-custom').text('R$ '+falta.toFixed(2).replace('.',','));
    $('#alerta-personalizado').toggleClass('d-none',Math.abs(falta)<=0.01);
}

$('#form-venda').submit(function(e){
    if($('#forma_pagamento').val()==='Personalizado'){
        let total=parseFloat($('#total-venda').val().replace(/\./g,'').replace(',','.'))||0,
            soma=$('.valor-parcela-personalizada').toArray().reduce((a,el)=>a+(parseFloat($(el).val().replace(/\./g,'').replace(',','.'))||0),0);
        if(Math.abs(total-soma)>0.01){e.preventDefault();$('#alerta-personalizado').removeClass('d-none').text('Soma das parcelas não fecha!');}
    }
});

$(document).ready(function(){
    atualizarTotais();
    if($('#forma_pagamento').val()==='Parcelado'){ $('#parcelamento').show(); gerarParcelas(); }
    if($('#forma_pagamento').val()==='Personalizado'){ $('#personalizado').show(); gerarPersonalizado(); }
});
</script>
@stop

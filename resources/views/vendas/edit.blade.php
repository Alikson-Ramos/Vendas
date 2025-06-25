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
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <div class="row mb-4">
                <div class="col-md-6">
                    <label><i class="fas fa-user text-primary"></i> Cliente (opcional)</label>
                    <select name="cliente_id" class="form-control">
                        <option value="">-- Selecione --</option>
                        @foreach($clientes as $c)
                            <option value="{{ $c->id }}"
                                {{ old('cliente_id', $venda->cliente_id)==$c->id?'selected':'' }}>
                                {{ $c->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label><i class="fas fa-credit-card text-primary"></i> Forma de Pagamento*</label>
                    <select name="forma_pagamento" id="forma_pagamento" class="form-control" required>
                        @foreach(['Dinheiro','Cartão','Parcelado','Personalizado'] as $fp)
                            <option value="{{ $fp }}"
                                {{ old('forma_pagamento', $venda->forma_pagamento)==$fp?'selected':'' }}>
                                {{ $fp }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>

            <h5><i class="fas fa-box text-primary"></i> Itens da Venda</h5>
            <table class="table table-bordered" id="tabela-itens">
                <thead>
                    <tr><th>Produto</th><th style="width:120px">Qtd</th><th style="width:180px">Preço Unit.</th><th style="width:180px">Total</th><th style="width:40px"></th></tr>
                </thead>
                <tbody>
                    @php $oldItens = old('itens', $venda->itens->toArray()); @endphp
                    @foreach($oldItens as $i => $item)
                    <tr class="item-row">
                        <td>
                            <select name="itens[{{ $i }}][produto_id]" class="form-control produto-select" required>
                                <option value="">Selecione</option>
                                @foreach($produtos as $p)
                                    <option value="{{ $p->id }}"
                                        {{ $item['produto_id']==$p->id?'selected':'' }}>
                                        {{ $p->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="itens[{{ $i }}][quantidade]" class="form-control quantidade" min="1" value="{{ $item['quantidade'] }}" required></td>
                        <td><input type="text" name="itens[{{ $i }}][preco_unitario]" class="form-control preco-unitario" value="{{ number_format($item['preco_unitario'] ?? 0,2,',','.') }}" required></td>
                        <td><input type="text" class="form-control total-item" value="{{ number_format(($item['preco_total'] ?? ($item['quantidade'] * ($item['preco_unitario'] ?? 0))),2,',','.') }}" readonly></td>
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

            <div id="parcelamento" style="display:{{ old('forma_pagamento',$venda->forma_pagamento)=='Parcelado'?'block':'none' }};margin-top:30px">
                <hr><h5><i class="fas fa-file-invoice-dollar text-primary"></i> Parcelamento</h5>
                <div class="row mb-2"><div class="col-md-4">
                    <label>Qtd. Parcelas</label>
                    @php $sel = old('qtd_parcelas')??$venda->parcelas->count(); @endphp
                    <select id="qtd_parcelas" class="form-control">
                        @for($i=2;$i<=12;$i++)<option value="{{ $i }}" {{ $sel==$i?'selected':'' }}>{{ $i }}x</option>@endfor
                    </select>
                </div></div>
                <table class="table table-sm" id="tabela-parcelas">
                    <thead><tr><th>#</th><th>Vencimento</th><th>Valor</th></tr></thead>
                    <tbody></tbody>
                </table>
            </div>

            <div id="personalizado" style="display:{{ old('forma_pagamento',$venda->forma_pagamento)=='Personalizado'?'block':'none' }};margin-top:30px">
                <hr><h5><i class="fas fa-file-invoice-dollar text-primary"></i> Parcelamento Personalizado</h5>
                <div class="row mb-2"><div class="col-md-4">
                    <label>Qtd. Parcelas</label>
                    @php $selP = old('qtd_parcelas_personalizado')??$venda->parcelas->count(); @endphp
                    <select id="qtd_parcelas_personalizado" class="form-control">
                        @for($i=2;$i<=12;$i++)<option value="{{ $i }}" {{ $selP==$i?'selected':'' }}>{{ $i }}x</option>@endfor
                    </select>
                </div></div>
                <table class="table table-bordered" id="tabela-personalizado">
                    <thead><tr><th>#</th><th>Vencimento</th><th>Valor</th></tr></thead>
                    <tbody>
                        @php
                            $rows = old('parcelas_personalizadas') 
                                ? old('parcelas_personalizadas') 
                                : $venda->parcelas->toArray();
                        @endphp
                        @foreach($rows as $i => $p)
                        <tr>
                            <td>{{ $i+1 }}<input type="hidden" name="parcelas_personalizadas[{{ $i }}][numero]" value="{{ $i+1 }}"></td>
                            <td><input type="date" name="parcelas_personalizadas[{{ $i }}][data_vencimento]" class="form-control" value="{{ old("parcelas_personalizadas.$i.data_vencimento",$p['data_vencimento']) }}"></td>
                            <td><input type="text" data-idx="{{ $i }}" name="parcelas_personalizadas[{{ $i }}][valor]" class="form-control valor-parcela-personalizada" value="{{ number_format(old("parcelas_personalizadas.$i.valor",$p['valor']),2,',','.') }}"></td>
                        </tr>
                        @endforeach
                    </tbody>
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
let precos = {
    @foreach($produtos as $p)"{{ $p->id }}":"{{ number_format($p->preco,2,',','.') }}",@endforeach
};

function atualizarTotais(){
    let total=0;
    $('#tabela-itens tr').each(function(){
        let q=+$(this).find('.quantidade').val()||0,
            pu=parseFloat($(this).find('.preco-unitario').val().replace(/\./g,'').replace(',','.'))||0,
            ti=q*pu;
        $(this).find('.total-item').val(ti.toFixed(2).replace('.',','));
        total+=ti;
    });
    $('#total-venda').val(total.toFixed(2).replace('.',','));
    if($('#forma_pagamento').val()==='Parcelado') gerarParcelas();
    if($('#forma_pagamento').val()==='Personalizado') gerarPersonalizado(true);
}

function gerarParcelas(){
    let qtd=+$('#qtd_parcelas').val(),
        total=parseFloat($('#total-venda').val().replace(/\./g,'').replace(',','.'))||0,
        base=Math.floor((total/qtd)*100)/100,
        resto=Math.round((total-base*qtd)*100)/100,
        hoje=new Date(),html='';
    for(let i=0;i<qtd;i++){
        let val=base+(i===qtd-1?resto:0),
            dt=new Date(hoje);
        dt.setMonth(hoje.getMonth()+i+1);
        html+=`<tr>
            <td>${i+1}<input type="hidden" name="parcelas[${i}][numero]" value="${i+1}"></td>
            <td><input type="date" name="parcelas[${i}][data_vencimento]" class="form-control" value="${dt.toISOString().slice(0,10)}"></td>
            <td><input type="text" name="parcelas[${i}][valor]" class="form-control" value="${val.toFixed(2).replace('.',',')}"></td>
        </tr>`;
    }
    $('#tabela-parcelas tbody').html(html);
}

function gerarPersonalizado(reset=false){
    let qtd=+$('#qtd_parcelas_personalizado').val(),
        total=parseFloat($('#total-venda').val().replace(/\./g,'').replace(',','.'))||0,
        base=Math.floor((total/qtd)*100)/100,
        resto=Math.round((total-base*qtd)*100)/100,
        hoje=new Date(),html='';
    for(let i=0;i<qtd;i++){
        let existing=$(`.valor-parcela-personalizada[data-idx="${i}"]`).val(),
            val = reset || existing===undefined
                ? (base + (i===qtd-1?resto:0))
                : parseFloat(existing.replace(/\./g,'').replace(',','.'))||0;
        let dtEl = $(`input[name="parcelas_personalizadas[${i}][data_vencimento]"]`).val(),
            dt = dtEl||new Date(hoje.getFullYear(),hoje.getMonth()+i+1,hoje.getDate()).toISOString().slice(0,10);
        html+=`<tr>
            <td>${i+1}<input type="hidden" name="parcelas_personalizadas[${i}][numero]" value="${i+1}"></td>
            <td><input type="date" name="parcelas_personalizadas[${i}][data_vencimento]" class="form-control" value="${dt}"></td>
            <td><input type="text" data-idx="${i}" name="parcelas_personalizadas[${i}][valor]" class="form-control valor-parcela-personalizada" value="${val.toFixed(2).replace('.',',')}"></td>
        </tr>`;
    }
    $('#tabela-personalizado tbody').html(html);
    recalcularPersonalizado();
}

function recalcularPersonalizado(){
    let total=parseFloat($('#total-venda').val().replace(/\./g,'').replace(',','.'))||0,
        inputs=$('.valor-parcela-personalizada'),
        vals=inputs.toArray().map(el=>parseFloat($(el).val().replace(/\./g,'').replace(',','.'))||0),
        soma=vals.reduce((a,b)=>a+b,0),
        falta=total-soma;
    $('#total-parcelas-custom').text('R$ '+soma.toFixed(2).replace('.',','));
    $('#falta-fechar-custom').text('R$ '+falta.toFixed(2).replace('.',','));
    $('#alerta-personalizado').toggleClass('d-none', Math.abs(falta)<=0.01);
    if(document.activeElement.classList.contains('valor-parcela-personalizada')){
        let idx=+document.activeElement.dataset.idx,
            rest=inputs.length-idx-1,
            saldo=total-vals.slice(0,idx+1).reduce((a,b)=>a+b,0),
            base=Math.floor((saldo/rest)*100)/100,
            rem=Math.round((saldo-base*rest)*100)/100;
        for(let j=idx+1;j<inputs.length;j++){
            let novo=base+(j===inputs.length-1?rem:0);
            $(`.valor-parcela-personalizada[data-idx="${j}"]`).val(novo.toFixed(2).replace('.',','));
        }
        recalcularPersonalizado();
    }
}

$(document)
  .on('change','.produto-select',function(){
      $(this).closest('tr').find('.preco-unitario').val(precos[$(this).val()]||'0,00').trigger('input');
  })
  .on('input change','.quantidade,.preco-unitario',atualizarTotais)
  .on('change','#qtd_parcelas',gerarParcelas)
  .on('change','#qtd_parcelas_personalizado',()=>gerarPersonalizado(true))
  .on('input','.valor-parcela-personalizada',recalcularPersonalizado)
  .on('click','.remover-item',function(){
      $(this).closest('tr').remove(); atualizarTotais();
  });

$('#adicionar-item').click(function(){
    let i=$('#tabela-itens tr').length,o='';
    @foreach($produtos as $p)o+=`<option value="{{$p->id}}">{{$p->nome}}</option>`;@endforeach
    $('#tabela-itens tbody').append(`
        <tr>
            <td><select name="itens[${i}][produto_id]" class="form-control produto-select">${o}</select></td>
            <td><input type="number" name="itens[${i}][quantidade]" class="form-control quantidade" min="1" value="1"></td>
            <td><input type="text" name="itens[${i}][preco_unitario]" class="form-control preco-unitario" value="0,00"></td>
            <td><input readonly class="form-control total-item" value="0,00"></td>
            <td><button type="button" class="btn btn-sm btn-danger remover-item"><i class="fa fa-trash"></i></button></td>
        </tr>`);
});

$('#forma_pagamento').change(function(){
    let v=$(this).val();
    $('#parcelamento,#personalizado').hide();
    if(v==='Parcelado') $('#parcelamento').show();
    if(v==='Personalizado') $('#personalizado').show();
    atualizarTotais();
});

$('#form-venda').submit(function(e){
    if($('#forma_pagamento').val()==='Personalizado'){
        let total=parseFloat($('#total-venda').val().replace(/\./g,'').replace(',','.'))||0,
            soma=$('.valor-parcela-personalizada').toArray().reduce((s,el)=>s+(parseFloat($(el).value.replace(/\./g,'').replace(',','.'))||0),0);
        if(Math.abs(total-soma)>0.01){
            e.preventDefault();
            $('#alerta-personalizado').removeClass('d-none').text('Soma das parcelas não fecha!');
        }
    }
});

$(document).ready(function(){
    atualizarTotais();
    if($('#forma_pagamento').val()==='Parcelado') gerarParcelas();
    if($('#forma_pagamento').val()==='Personalizado') gerarPersonalizado(true);
});
</script>
@stop

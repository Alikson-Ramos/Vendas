<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\ItemVenda;
use App\Models\Parcela;
use App\Models\Cliente;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VendaController extends Controller
{
    public function index()
    {
        $vendas = Venda::with('cliente', 'user')->orderByDesc('created_at')->paginate(10);
        return view('vendas.index', compact('vendas'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        return view('vendas.create', compact('clientes', 'produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'itens' => 'required|array|min:1',
            'itens.*.produto_id' => 'required|exists:produtos,id',
            'itens.*.quantidade' => 'required|integer|min:1',
            'itens.*.preco_unitario' => 'required',
            'forma_pagamento' => 'required|string',
            'parcelas' => 'nullable|array'
        ]);

        $total = 0;
        foreach($request->itens as $item) {
            $preco = floatval(str_replace(['.', ','], ['', '.'], $item['preco_unitario']));
            $preco_total = $preco * $item['quantidade'];
            $total += $preco_total;
        }

        $venda = Venda::create([
            'cliente_id'      => $request->cliente_id,
            'user_id'         => Auth::id(),
            'total'           => $total,
            'forma_pagamento' => $request->forma_pagamento,
        ]);

        foreach($request->itens as $item) {
            $preco = floatval(str_replace(['.', ','], ['', '.'], $item['preco_unitario']));
            $preco_total = $preco * $item['quantidade'];
            $venda->itens()->create([
                'produto_id'    => $item['produto_id'],
                'quantidade'    => $item['quantidade'],
                'preco_unitario'=> $preco,
                'preco_total'   => $preco_total,
            ]);
        }

        if ($request->forma_pagamento === 'Parcelado' && isset($request->parcelas)) {
            foreach($request->parcelas as $p) {
                $venda->parcelas()->create([
                    'numero'          => $p['numero'],
                    'data_vencimento' => $p['data_vencimento'],
                    'valor'           => floatval(str_replace(['.', ','], ['', '.'], $p['valor'])),
                ]);
            }
        }

        return redirect()->route('vendas.index')->with('msgSuccess', 'Venda cadastrada com sucesso!');
    }

    public function show(Venda $venda)
    {
        $venda->load('cliente', 'user', 'itens.produto', 'parcelas');
        return view('vendas.show', compact('venda'));
    }

    public function edit(Venda $venda)
    {
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();
        $venda->load('itens', 'parcelas');
        return view('vendas.edit', compact('venda', 'clientes', 'produtos'));
    }

    public function update(Request $request, Venda $venda)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'itens' => 'required|array|min:1',
            'itens.*.produto_id' => 'required|exists:produtos,id',
            'itens.*.quantidade' => 'required|integer|min:1',
            'itens.*.preco_unitario' => 'required',
            'forma_pagamento' => 'required|string',
            'parcelas' => 'nullable|array'
        ]);

        $total = 0;
        foreach($request->itens as $item) {
            $preco = floatval(str_replace(['.', ','], ['', '.'], $item['preco_unitario']));
            $preco_total = $preco * $item['quantidade'];
            $total += $preco_total;
        }

        $dados = [
            'cliente_id'      => $request->cliente_id,
            'total'           => $total,
            'forma_pagamento' => $request->forma_pagamento,
        ];
        $venda->update($dados);

        $venda->itens()->delete();
        foreach($request->itens as $item) {
            $preco = floatval(str_replace(['.', ','], ['', '.'], $item['preco_unitario']));
            $preco_total = $preco * $item['quantidade'];
            $venda->itens()->create([
                'produto_id'    => $item['produto_id'],
                'quantidade'    => $item['quantidade'],
                'preco_unitario'=> $preco,
                'preco_total'   => $preco_total,
            ]);
        }

        $venda->parcelas()->delete();
        if ($request->forma_pagamento === 'Parcelado' && isset($request->parcelas)) {
            foreach($request->parcelas as $p) {
                $venda->parcelas()->create([
                    'numero'          => $p['numero'],
                    'data_vencimento' => $p['data_vencimento'],
                    'valor'           => floatval(str_replace(['.', ','], ['', '.'], $p['valor'])),
                ]);
            }
        }

        return redirect()->route('vendas.index')->with('msgSuccess', 'Venda atualizada com sucesso!');
    }

    public function destroy(Venda $venda)
    {
        $venda->delete();
        return redirect()->route('vendas.index')->with('msgSuccess', 'Venda removida com sucesso!');
    }
}

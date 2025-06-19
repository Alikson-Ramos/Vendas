<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::orderBy('nome')->paginate(10);
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required',
        ]);

        $dados = $request->all();
        $dados['preco'] = str_replace(['.', ','], ['', '.'], $dados['preco']);

        Produto::create($dados);

        return redirect()->route('produtos.index')
            ->with('msgSuccess', 'Produto cadastrado com sucesso!');
    }

    public function show(Produto $produto)
    {
        return view('produtos.show', compact('produto'));
    }

    public function edit(Produto $produto)
    {
        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required',
        ]);

        $dados = $request->all();
        $dados['preco'] = str_replace(['.', ','], ['', '.'], $dados['preco']);

        $produto->update($dados);

        return redirect()->route('produtos.index')
            ->with('msgSuccess', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();

        return redirect()->route('produtos.index')
            ->with('msgSuccess', 'Produto removido com sucesso!');
    }
}

@extends('adminlte::page')

@section('title', 'Lista de Vendas')

@section('content_header')
    @if(Session::has('msgSuccess'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <i class="fa-regular fa-bell" style="margin-right: 5px"></i> {!! Session::get('msgSuccess') !!}
        </div>
    @endif
    <div class="container-fluid">
        <h1>Lista de Vendas</h1>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-cash-register" style="margin-right: 5px;"></i> Vendas Realizadas</h3>
        <div class="card-tools d-flex align-items-center">
            <a href="{{ route('vendas.create') }}" class="btn btn-block btn-default btn-sm" style="margin-right: 10px;">
                <i class="fa-solid fa-plus" style="margin-right: 5px;"></i> Nova Venda
            </a>
        </div>
    </div>
    <div class="card-body mr-1">
        <table class="table datatable dtr-inline mr-1 ml-1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Total</th>
                    <th>Forma de Pagamento</th>
                    <th style="width: 90px">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vendas as $venda)
                    <tr>
                        <td>{{ $venda->id }}</td>
                        <td>{{ $venda->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $venda->cliente->nome ?? '-' }}</td>
                        <td>{{ $venda->user->name ?? '-' }}</td>
                        <td>R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                        <td>{{ $venda->forma_pagamento }}</td>
                        <td>
                            <a href="{{ route('vendas.show', $venda->id) }}" class="btn btn-sm btn-tool" title="Visualizar">
                                <i class="fa-solid fa-eye" style="color: green"></i>
                            </a>
                            <a href="{{ route('vendas.edit', $venda->id) }}" class="btn btn-sm btn-tool" title="Editar">
                                <i class="fa-solid fa-pencil" style="color: #008ca5"></i>
                            </a>
                            <form action="{{ route('vendas.destroy', $venda->id) }}" method="POST" class="d-inline" id="removerForm_{{$venda->id}}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-tool" title="Remover" onclick="confirmDeletar({{$venda->id}})">
                                    <i class="fa-regular fa-trash-can" style="color: darkred"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer text-right">
        {{ $vendas->links() }}
    </div>
</div>
@stop

@section('js')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script>
        function confirmDeletar(id) {
            Swal.fire({
                title: 'Remover Venda!',
                text: 'Esta ação vai remover a Venda. Você tem certeza?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#008ca5',
                cancelButtonColor: '#5fc3b4',
                confirmButtonText: 'Sim, Remover',
                cancelButtonText: 'Cancelar',
                iconHtml: '<i class="fa-solid fa-exclamation-circle text-danger" style="font-size: 1.5em;"></i> ',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('removerForm_' + id).submit();
                }
            });
        }
    </script>
@stop

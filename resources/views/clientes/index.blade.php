@extends('adminlte::page')

@section('title', 'Lista de Clientes')

@section('content_header')
    @if(Session::has('msgSuccess'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <i class="fa-regular fa-bell" style="margin-right: 5px"></i> {!! Session::get('msgSuccess') !!}
        </div>
    @elseif(Session::has('msgError'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <i class="fa-solid fa-triangle-exclamation"></i> {!! Session::get('msgError') !!}
        </div>
    @endif

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Início</a></li>
                    <li class="breadcrumb-item active">Clientes</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-building-user" style="margin-right: 5px;"></i> Lista de Clientes</h3>
        <div class="card-tools d-flex align-items-center">
            <button type="button" class="btn btn-block btn-default btn-sm" onclick="window.location.href='{{ route('clientes.create') }}'" style="margin-right: 10px;">
                <i class="fa-solid fa-plus" style="margin-right: 5px;"></i> Incluir Cliente
            </button>
        </div>
    </div>
    @if ($clientes->count() > 0)
        <div class="card-body mr-1">
            <table class="table datatable dtr-inline mr-1 ml-1">
                <thead>
                    <tr>
                        <th style="width: 35%">Nome</th>
                        <th style="width: 25%">E-mail</th>
                        <th style="width: 20%">Telefone</th>
                        <th style="width: 10%">Criado em</th>
                        <th style="width: 10%">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                        <tr>
                            <td>
                                <i class="fa-solid fa-user" style="margin-right: 5px;color: #008ca5"></i>
                                {{ $cliente->nome }}
                            </td>
                            <td>{{ $cliente->email }}</td>
                            <td>{{ $cliente->telefone }}</td>
                            <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-sm btn-tool" title="Visualizar">
                                    <i class="fa-solid fa-eye" style="color: green"></i>
                                </a>
                                <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-tool" title="Editar">
                                    <i class="fa-solid fa-pencil" style="color: #008ca5"></i>
                                </a>
<form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline" id="removerForm_{{$cliente->id}}">
    @csrf
    @method('DELETE')
    <input type="hidden" name="destroy_id" id="deleteIdInput">
    <button type="button" class="btn btn-sm btn-tool" title="Remover" onclick="confirmDeletar({{$cliente->id}})">
        <i class="fa-regular fa-trash-can" style="color: darkred"></i>
    </button>
</form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="row" style="margin: 20px;">
            <div class="callout callout-warning">
                <h5><i class="fa-solid fa-circle-info"></i> Nenhum Cliente foi encontrado.</h5>
                <p>Cadastre seu Cliente no botão <strong>"Incluir Cliente"</strong> no canto superior direito</p>
            </div>
        </div>
    @endif
    <div class="card-footer text-right">
        <a href="#" class="btn btn-sm btn-tool">
            <i class="fa-solid fa-circle-info"></i>
        </a>
    </div>
</div>
@stop

@section('js')
    {{-- Script Global (Paginação DataTables) --}}
    <script src="{{ asset('js/utils.js') }}"></script>

    {{-- Exclusão de Cliente --}}
    <script>
        function confirmDeletar(id) {
            Swal.fire({
                title: 'Remover Cliente!',
                text: 'Esta ação vai remover o Cliente. Você tem certeza?',
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
                    document.getElementById('deleteIdInput').value = id;
                    document.getElementById('removerForm_' + id).submit();
                }
            });
        }
    </script>
@stop

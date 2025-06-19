# PROJETO Sistema de Vendas

## Sobre o Projeto

Sistema simples e robusto para gerenciamento de vendas de produtos, com cadastro de clientes, produtos e vendedores, controle de formas de pagamento (à vista, cartão, parcelado), geração automática de parcelas, filtros inteligentes.
---

## Requisitos do Projeto

- Ubuntu 22.04 LTS (ou outro SO compatível)
- Node.js v20.12.2+
- NPM v10.5.2+
- Laravel v10.48.10+
- AdminLTE 3.8.6+
- PHP v8.3.6+
- MySQL v8.0.33+
- Composer v2.7.4+

---
## Instalação

### Clonando o repositório

```bash
git clone https://github.com/Alikson-Ramos/Vendas.git
cd Vendas
```
## Instalar dependências PHP e Node
```
composer install
npm install
```

## Instalar Laravel
Documentação: <a href="https://laravel.com/docs/12.x"> ref : LARAVEL </a>
 ```
composer create-project laravel/laravel vendas
```
- Permissão pasta storage
```
sudo chmod 777 -R storage/
```
## Base de dados MySql
- No arquivo .env
```
DB_CONNECTION=mysql 
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=NomeDaBaseDeDados
DB_USERNAME=UsuarioDaBase
DB_PASSWORD=SenhaDaBase
```
## Gere a key do Laravel:
```
php artisan key:generate
```
## Instalando Migrations
```
php artisan migrate:install
```
```
php artisan migrate:status
```
```
php artisan migrate
```
## AdminLTE 3

Documentação: <a href="https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Installation"> ref : Doc AdminLTE</a>

- Baixar pacote
```bash
composer require jeroennoten/laravel-adminlte
```

- Instalar Dependencia
```bash
php artisan adminlte:install
```

- Definir Autenticação
```bash
composer require laravel/ui
php artisan ui bootstrap --auth
```

- Instalar NPM no Projeto
```bash
npm install && npm run dev
```

- Reemplazar Views
```bash
php artisan adminlte:install --only=auth_views
```

- Realizar ATUALIZAÇÃO de versão
```bash
composer update jeroennoten/laravel-adminlte
```

- Instalar UPDATE
```bash
php artisan adminlte:update
```

- Instalação de Backup AdminLTE (local)
```bash
php artisan adminlte:install --only=main_views --force
```
### HOME interface

```php
@extends('adminlte::page')

@section('title', 'System | HAGUEN')

@section('content_header')
    <h1>Gerenciador de Documentos</h1>
@stop

@section('content')
    <p>Gerenciador de Documentos HAGUEN.</p>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop

```

## Instalar rota da API
```
php artisan install:api
```

## FONTAWESOME ICONS

Referencia: <a href="https://fontawesome.com/download"> ref : fontawesome</a>
- Procurar Free For WEB
- Fazer Download do Pacote em ZIP.
- Descompactar
- Cópiar para a PASTA public do projeto.
- Editar a referência no arquivo BLADE MASTER
Caminho: resource/views/vendor/adminlte/master.blade.php
```php
    {{-- Icons Fontawesome --}}
    <link rel="stylesheet" href="{{asset('fontawesome/css/all.min.css')}}">
```

## Alterar Idioma
```
    'timezone' => 'America/Sao_Paulo',
    'locale' => 'pt-br',
```

## Seeders

```bash
php artisan db:seed --class=DatabaseSeeder
```

## Configuração do Sweetalert2
```php
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    // 'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                    'location' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
```

## Tela de pedidos
![image](https://github.com/user-attachments/assets/14c126da-e993-4d21-b9aa-35a341091cd4)

## Alteração de nº de parcelas e atualização de valores automaticos
![image](https://github.com/user-attachments/assets/2a27ba45-4519-420f-a170-3f1180ef1113)



## Desenvolvido por Alikson Ramos.


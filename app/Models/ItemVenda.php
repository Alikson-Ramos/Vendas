<?php

// app/Models/ItemVenda.php
namespace App\Models;

use App\Models\Venda;
use Illuminate\Database\Eloquent\Model;

class ItemVenda extends Model
{
    protected $fillable = [
        'venda_id', 'produto_id', 'quantidade', 'preco_unitario', 'preco_total'
    ];

    public function venda()
    { 
        return $this->belongsTo(Venda::class); 
    }

    public function produto()
    { 
        return $this->belongsTo(Produto::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendaFormaPagamento extends Model
{
    protected $fillable = ['venda_id', 'forma_pagamento_id',"valor"];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }

    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class);
    }
}

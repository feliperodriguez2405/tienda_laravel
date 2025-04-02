<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'ordenes_compra';

    protected $fillable = [
        'proveedor_id',
        'fecha',
        'monto',
        'estado',
        'detalles',
        'confirmado_por_vendedor',
    ];

    protected $casts = [
        'detalles' => 'array',
        'fecha' => 'datetime',
        'confirmado_por_vendedor' => 'boolean',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
}
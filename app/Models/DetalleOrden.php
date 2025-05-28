<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOrden extends Model
{
    use HasFactory;

    // Especificamos explícitamente el nombre de la tabla
    protected $table = 'detalle_ordenes';

    // Campos que se pueden llenar masivamente
    protected $fillable = ['orden_id', 'producto_id', 'cantidad', 'subtotal'];

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    // Relación con Orden
    public function orden()
    {
        return $this->belongsTo(Orden::class, 'orden_id');
    }
}
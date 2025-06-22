<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Proveedor extends Model
{
    use Notifiable;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'direccion',
        'productos_suministrados',
        'condiciones_pago',
        'fecha_vencimiento_contrato',
        'recibir_notificaciones',
        'estado',
        'categoria_id',
    ];

    protected $casts = [
        'productos_suministrados' => 'array',
        'fecha_vencimiento_contrato' => 'datetime',
        'recibir_notificaciones' => 'boolean',
    ];

    public function ordenesCompra()
    {
        return $this->hasMany(OrdenCompra::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
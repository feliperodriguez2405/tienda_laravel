<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Proveedor extends Model
{
    use HasFactory, Notifiable; // Agregamos Notifiable

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'direccion',
        'productos_suministrados',
        'condiciones_pago',
        'estado',
        'fecha_vencimiento_contrato',
        'recibir_notificaciones',
    ];

    protected $casts = [
        'productos_suministrados' => 'array',
        'estado' => 'string',
        'fecha_vencimiento_contrato' => 'datetime',
        'recibir_notificaciones' => 'boolean',
    ];

    public function ordenesCompra()
    {
        return $this->hasMany(OrdenCompra::class, 'proveedor_id');
    }
}
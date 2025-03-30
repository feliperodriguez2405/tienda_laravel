<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    // Especificamos explícitamente el nombre de la tabla
    protected $table = 'ordenes';

    // Campos que se pueden llenar masivamente
    protected $fillable = ['user_id', 'total', 'estado'];

    // Definimos los valores permitidos para 'estado' como un cast (opcional, pero útil)
    protected $casts = [
        'estado' => 'string', // Compatible con el enum
    ];

    // Relación con DetalleOrden (una orden tiene muchos detalles)
    public function detalles()
    {
        return $this->hasMany(DetalleOrden::class, 'orden_id');
    }

    // Relación con User (una orden pertenece a un usuario)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
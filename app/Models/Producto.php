<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Ya tenías esto, pero lo confirmamos
    protected $table = 'productos';

    // Campos que se pueden llenar masivamente
    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'categoria_id', 'imagen'];

    // Relación con Categoria
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    // Relación con DetalleOrden
    public function detallesOrdenes()
    {
        return $this->hasMany(DetalleOrden::class, 'producto_id');
    }
}
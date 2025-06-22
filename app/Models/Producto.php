<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = ['nombre', 'descripcion', 'precio', 'precio_compra', 'stock', 'estado', 'categoria_id', 'imagen'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function detallesOrdenes()
    {
        return $this->hasMany(DetalleOrden::class, 'producto_id');
    }
}
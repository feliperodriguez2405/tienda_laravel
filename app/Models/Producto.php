<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = ['nombre', 'codigo_barra', 'descripcion', 'precio', 'precio_compra', 'stock', 'estado', 'categoria_id', 'imagen'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function detallesOrdenes()
    {
        return $this->hasMany(DetalleOrden::class, 'producto_id');
    }

    /**
     * Boot the model and register event listeners.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically set estado to 'inactivo' if precio is 0 or null during save
        static::saving(function ($producto) {
            if ($producto->precio <= 0 || is_null($producto->precio)) {
                $producto->estado = 'inactivo';
            }
        });
    }
}
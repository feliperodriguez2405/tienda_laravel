<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos'; // Nombre de la tabla en la BD

    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'categoria_id', 'imagen'];

    // Relación con Categoria (un producto pertenece a una categoría)
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    protected $table = 'ordenes'; // Explicitly set the table name

    protected $fillable = ['user_id', 'total', 'estado', 'metodo_pago'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleOrden::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    use HasFactory;

    protected $table = 'metodos_pago'; // Explicitly set the table name

    protected $fillable = ['nombre'];

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
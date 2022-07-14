<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    protected $fillable = [
        'abreviatura', 'nombre', 'direccion', 'estado'
    ];

    public function dependencias()
    {
        return $this->hasMany(Dependencia::class, 'sede_id');
    }
}

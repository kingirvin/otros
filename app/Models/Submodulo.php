<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submodulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'modulo_id', 'titulo', 'nombre', 'descripcion', 'estado'
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    public function privilegios()
    {
        return $this->hasMany(Privilegio::class, 'submodulo_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitado extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_id', 'ruc', 'razon_social', 'dependencia', 'cargo', 'correo', 'telefono', 'direccion', 'estado'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }


}

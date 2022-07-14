<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_id', 'codigo', 'facultad', 'condicion', 'correo', 'estado', 
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }



}

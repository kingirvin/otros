<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'dependencia_id', 'persona_id', 'cargo', 'fecha_inicio', 'fecha_termino', 'estado'
    ];

    protected $dates = [
        'fecha_inicio', 'fecha_termino'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_id');
    }

}

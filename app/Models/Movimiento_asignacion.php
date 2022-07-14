<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento_asignacion extends Model
{
    use HasFactory;

    protected $table = 'movimiento_asignaciones';

    protected $fillable = [
        'movimiento_id', 'empleado_id', 'persona_id', 'accion_id', 'detalles', 'estado', 'user_id'
    ];

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class, 'movimiento_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function accion()
    {
        return $this->belongsTo(Accion::class, 'accion_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

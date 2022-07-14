<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'sede_id', 'dependencia_id', 'abreviatura', 'nombre', 'descripcion', 'correo', 'telefono', 'estado'
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'dependencia_id');
    }
}

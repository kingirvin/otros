<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{    
    use HasFactory;

    protected $fillable = [
        'tipo', 'identidad_documento_id', 'nro_documento', 'nombre', 'apaterno', 'amaterno', 'correo', 'telefono', 'direccion', 'nacimiento', 'estado'
    ];

    protected $dates = [
        'nacimiento'
    ];    

    public function identidad_documento()
    {
        return $this->belongsTo(Identidad_documento::class, 'identidad_documento_id');
    }

    public function empleos()
    {
        return $this->hasMany(Empleado::class, 'persona_id');
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'persona_id');
    }

    public function invitados()
    {
        return $this->hasMany(Invitado::class, 'persona_id');
    }    

    public function users()
    {
        return $this->hasMany(User::class, 'persona_id');
    }

}

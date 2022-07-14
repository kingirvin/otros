<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedimiento_paso extends Model
{
    use HasFactory;

    protected $fillable = [
        'procedimiento_id', 'dependencia_id', 'orden', 'accion', 'descripcion', 'plazo_atencion', 'plazo_subsanacion', 'estado', 
    ];

    public function procedimiento()
    {
        return $this->belongsTo(Procedimiento::class, 'procedimiento_id');
    }

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_id');
    }


}

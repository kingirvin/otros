<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo', 'codigo', 'titulo', 'descripcion', 'requisitos', 'normatividad', 'presentar_id', 'presentar_modalidad', 'pago_monto', 'pago_entidad', 'pago_codigo', 'plazo', 'calificacion', 'atender_id', 'atender_modalidad', 'estado', 
    ];

    public function presentar()
    {
        return $this->belongsTo(Dependencia::class, 'presentar_id');
    }

    public function atender()
    {
        return $this->belongsTo(Dependencia::class, 'atender_id');
    }

    public function pasos()
    {
        return $this->hasMany(Procedimiento_paso::class, 'procedimiento_id');
    }   
    
    public function tramites()
    {
        return $this->hasMany(Tramite::class, 'procedimiento_id');
    }
    


}

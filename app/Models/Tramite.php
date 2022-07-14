<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
    use HasFactory;

    protected $fillable = [
        'year', 'correlativo', 'codigo', 'o_tipo', 'o_externo_tipo', 'o_dependencia_id', 'o_user_id', 'o_identidad_documento_id', 'o_nro_documento', 'o_nombre', 'o_apaterno', 'o_amaterno', 'o_telefono', 'o_correo', 'o_direccion', 'procedimiento_id', 'observaciones', 'user_id', 'estado', 
    ];

    public function o_dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'o_dependencia_id');
    }

    public function o_user()
    {
        return $this->belongsTo(User::class, 'o_user_id');
    }

    public function o_identidad_documento()
    {
        return $this->belongsTo(Identidad_documento::class, 'o_identidad_documento_id');
    }

    public function procedimiento()
    {
        return $this->belongsTo(Procedimiento::class, 'procedimiento_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'tramite_id');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'tramite_id');
    }

    public function observaciones()
    {
        return $this->hasMany(Movimiento_observacion::class, 'tramite_id');
    }

    
}

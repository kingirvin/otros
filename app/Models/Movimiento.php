<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'tramite_id', 'documento_id', 'accion_id', 'accion_otros', 'anterior_id', 'tipo', 'copia', 'o_tipo', 'o_dependencia_id', 'o_fecha', 'o_user_id', 'o_year', 'o_numero', 'o_descripcion', 'd_tipo', 'd_dependencia_id', 'd_identidad_documento_id', 'd_nro_documento', 'd_nombre', 'd_fecha', 'd_user_id', 'd_year', 'd_numero', 'd_observacion', 'f_user_id', 'f_fecha', 'f_observacion', 'asignaciones', 'estado',
    ];

    protected $dates = [
        'o_fecha', 'd_fecha', 'f_fecha'
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class, 'tramite_id');
    }

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }

    public function accion()
    {
        return $this->belongsTo(Accion::class, 'accion_id');
    }

    public function anterior()
    {
        return $this->belongsTo(Movimiento::class, 'anterior_id');
    }

    public function siguientes()
    {
        return $this->hasMany(Movimiento::class, 'anterior_id');
    }

    public function o_dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'o_dependencia_id');
    }

    public function o_user()
    {
        return $this->belongsTo(User::class, 'o_user_id');
    }

    public function d_dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'd_dependencia_id');
    }

    public function destino_identidad()
    {
        return $this->belongsTo(Identidad_documento::class, 'd_identidad_documento_id');
    }

    public function d_user()
    {
        return $this->belongsTo(User::class, 'd_user_id');
    }

    public function f_user()
    {
        return $this->belongsTo(User::class, 'f_user_id');
    }

    public function observaciones()
    {
        return $this->hasMany(Movimiento_observacion::class, 'movimiento_id');
    }


}

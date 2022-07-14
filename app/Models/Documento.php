<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'year', 'correlativo', 'codigo', 'tramite_id', 'dependencia_id', 'o_numero', 'documento_tipo_id', 'numero', 'remitente', 'asunto', 'folios', 'observaciones', 'anexos_url', 'archivo_id', 'user_id', 
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class, 'tramite_id');
    }

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_id');
    }

    public function documento_tipo()
    {
        return $this->belongsTo(Documento_tipo::class, 'documento_tipo_id');
    }

    public function archivo()
    {
        return $this->belongsTo(Archivo::class, 'archivo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'documento_id');
    }

    public function anexos()
    {
        return $this->hasMany(Documento_anexo::class, 'documento_id');
    }


}

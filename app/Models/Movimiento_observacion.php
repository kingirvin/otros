<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento_observacion extends Model
{
    use HasFactory;

    protected $table = 'movimiento_observaciones';

    protected $fillable = [
        'tramite_id', 'movimiento_id', 'user_id', 'detalle', 
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class, 'tramite_id');
    }

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class, 'movimiento_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}

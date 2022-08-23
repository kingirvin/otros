<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_restablecimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'codigo', 'fecha_inicio', 'fecha_vencimiento', 'estado'
    ];

    protected $dates = [
        'fecha_inicio',
        'fecha_vencimiento'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

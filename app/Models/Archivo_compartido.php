<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo_compartido extends Model
{
    use HasFactory;
    public $incrementing = false;
    
    protected $fillable = [
        'archivo_id', 'user_id', 'estado'
    ];

    public function archivo()
    {
        return $this->belongsTo(Archivo::class, 'archivo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento_tipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'abreviatura', 'estado'
    ];

    
    public function documentos()
    {
        return $this->hasMany(Documento::class, 'documento_tipo_id');
    }
    
}

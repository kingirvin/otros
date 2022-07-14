<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento_anexo extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'documento_id', 'archivo_id', 'principal'
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }

    public function archivo()
    {
        return $this->belongsTo(Archivo::class, 'archivo_id');
    }

}

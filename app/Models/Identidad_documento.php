<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identidad_documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'abreviatura', 'nombre', 'descripcion', 'largo', 'estado'
    ]; 
   
}

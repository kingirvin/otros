<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilegio extends Model
{
    use HasFactory;

    public $incrementing = false;
    
    protected $fillable = [
        'rol_id', 'submodulo_id'
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function submodulo()
    {
        return $this->belongsTo(Submodulo::class, 'submodulo_id');
    }
}

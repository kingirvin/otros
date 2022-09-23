<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cert_repositorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function carpetas()
    {
        return $this->hasMany(Cert_carpeta::class, 'cert_repositorio_id');
    }

    public function archivos()
    {
        return $this->hasMany(Cert_archivo::class, 'cert_repositorio_id');
    }

    public function responsables()
    {
        return $this->hasMany(Cert_repositorio_user::class, 'cert_repositorio_id');
    }
}

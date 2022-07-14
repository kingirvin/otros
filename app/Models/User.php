<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $appends = ['siglas'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo', 'codigo', 'rol_id', 'persona_id', 'identidad_documento_id', 'nro_documento', 'nombre', 'apaterno', 'amaterno', 'email', 'password', 'estado'
    ];
 
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'nacimiento'
    ];

    public function getSiglasAttribute()
    {
        $n = strtoupper(substr(trim($this->nombre),0,1));
        $p = strtoupper(substr(trim($this->apaterno),0,1));        
        return $n.$p;
    }   

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function identidad_documento()
    {
        return $this->belongsTo(Identidad_documento::class, 'identidad_documento_id');
    }
}

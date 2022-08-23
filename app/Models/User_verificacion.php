<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_verificacion extends Model
{
    use HasFactory;

    protected $table = 'user_verificaciones';

    protected $fillable = [
        'user_id', 'codigo', 'estado'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

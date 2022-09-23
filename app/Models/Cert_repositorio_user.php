<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cert_repositorio_user extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'cert_repositorio_id', 'user_id'
    ];

    public function repositorio()
    {
        return $this->belongsTo(Cert_repositorio::class, 'cert_repositorio_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

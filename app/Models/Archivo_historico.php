<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utilidades\Recursos;

class Archivo_historico extends Model
{
    use HasFactory;

    protected $fillable = [
        'archivo_id', 'user_id', 'formato', 'size', 'ruta', 'nombre_real', 'estado', 
    ];

    protected $appends = [ 'format_size', 'ruta_publica', 'ruta_storage' ];

    public function getFormatSizeAttribute(){
        $recursos = new Recursos();        
        return $recursos->bytes_format($this->size);
    }

    public function getRutaPublicaAttribute()
    {
        return 'storage/'.$this->ruta;
    }

    public function getRutaStorageAttribute()
    {
        return 'app/public/'.$this->ruta;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}

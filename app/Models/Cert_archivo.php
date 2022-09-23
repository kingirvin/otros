<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utilidades\Recursos;

class Cert_archivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'cert_repositorio_id', 'cert_carpeta_id', 'user_id', 'codigo', 'cvd', 'nombre', 'formato', 'size', 'ruta',
        'nombre_real', 'descripcion', 'informacion', 'para_firma', 'estado', 'publico'
    ];

    protected $appends = [ 'format_size', 'format_cvd', 'ruta_publica', 'ruta_storage' ];

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

    public function getFormatCvdAttribute()
    {
        if($this->cvd != "")
            return substr($this->cvd,0,4).' '.substr($this->cvd,4,4).' '.substr($this->cvd,8,4).' '.substr($this->cvd,12,4);
        else
            return "";
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function carpeta()
    {
        return $this->belongsTo(Cert_carpeta::class, 'cert_carpeta_id');
    }

    public function repositorio()
    {
        return $this->belongsTo(Cert_repositorio::class, 'cert_repositorio_id');
    }
}

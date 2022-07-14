<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Utilidades\Recursos;

class Archivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'dependencia_id', 'user_id', 'carpeta_id', 'codigo', 'cvd', 'nombre', 'formato', 'size', 'ruta', 'nombre_real', 'descripcion', 'informacion', 'para_firma', 'estado', 'publico', 
    ];

    //datos adicionales
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

    //relaciones
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function carpeta()
    {
        return $this->belongsTo(Carpeta::class, 'carpeta_id');
    }

    public function historicos()
    {
        return $this->hasMany(Archivo_historico::class, 'archivo_id');
    }

    public function compartidos()
    {
        return $this->hasMany(Archivo_compartido::class, 'archivo_id');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'archivo_id');
    }

    public function anexados()
    {
        return $this->hasMany(Documento_anexo::class, 'archivo_id');
    }

    


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cert_carpeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'cert_repositorio_id', 'user_id', 'cert_carpeta_id', 'codigo', 'nombre', 'ubicacion', 'publico'
    ];

    public function repositorio()
    {
        return $this->belongsTo(Cert_repositorio::class, 'cert_repositorio_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function padre()
    {
        return $this->belongsTo(Cert_carpeta::class, 'cert_carpeta_id');
    }

    public function archivos()
    {
        return $this->hasMany(Cert_archivo::class, 'cert_carpeta_id');
    }

    public function subcarpetas()
    {
        return $this->hasMany(Cert_carpeta::class, 'cert_carpeta_id');
    }

    public function ruta()
    {
        if($this->ubicacion != '')
            return self::whereIn('id', explode(",", $this->ubicacion))->get();  
        else
            return collect();
    }

    public static function generar_ubicacion($padre_id) 
    {        
        if($padre_id != 0)
        {
            $padre = self::where('id', $padre_id)->first();  
            $ids = explode(",", $padre->ubicacion);
            $ids[] = $padre_id;
            return implode(",", $ids);
        }
        else
            return '';
    }

    public function sub_update()
    {
        $subcarpetas = self::where('cert_carpeta_id', $this->id)->get();
        foreach ($subcarpetas as $subcrp) {
            $ids = explode(",", $this->ubicacion);
            $ids[] = $this->id;
            $subcrp->ubicacion = implode(",", $ids);
            $subcrp->save();
            $subcrp->sub_update();
        }
    }
}

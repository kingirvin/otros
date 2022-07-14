<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carpeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'dependencia_id', 'user_id', 'carpeta_id', 'codigo', 'nombre', 'ubicacion', 'publico',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function padre()
    {
        return $this->belongsTo(Carpeta::class, 'carpeta_id');
    }

    public function archivos()
    {
        return $this->hasMany(Archivo::class, 'carpeta_id');
    }

    public function subcarpetas()
    {
        return $this->hasMany(Carpeta::class, 'carpeta_id');
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
        $subcarpetas = self::where('carpeta_id', $this->id)->get();
        foreach ($subcarpetas as $subcrp) {
            $ids = explode(",", $this->ubicacion);
            $ids[] = $this->id;
            $subcrp->ubicacion = implode(",", $ids);
            $subcrp->save();
            $subcrp->sub_update();
        }
    }


}

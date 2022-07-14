<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Privilegio;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'nombre', 'descripcion', 'estado'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'rol_id');
    }

    public function privilegios()
    {
        return $this->hasMany(Privilegio::class, 'rol_id');
    }

    public function modulos()
    {
        $privilegios = Privilegio::where('rol_id', $this->id)->with('submodulo.modulo')->get();
        //obtenemos solo los módulos en texto
        $mod_array = array();
        foreach ($privilegios as $privilegio) {
            $mod_text = $privilegio->submodulo->modulo->nombre;
            if(!in_array($mod_text, $mod_array))
                $mod_array[] = $mod_text;
        }
        //obtenemos los submodulos para cada modulo
        $modulos = array();
        foreach ($mod_array as $modulo) {
            $submodulos = array();
            foreach ($privilegios as $privilegio) {
                if($privilegio->submodulo->modulo->nombre == $modulo)
                    $submodulos[] = $privilegio->submodulo->nombre;
            }
            $modulos[$modulo] = $submodulos;
        }
        return $modulos;
    }
}

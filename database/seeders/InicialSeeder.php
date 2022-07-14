<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InicialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $identidad_id = DB::table('identidad_documentos')->insertGetId([
            'abreviatura' => 'DNI',
            'nombre' => 'DOCUMENTO NACIONAL DE IDENTIDAD',
            'descripcion' => 'DOCUMENTO NACIONAL DE IDENTIDAD',
            'largo' => 8,
        ]);

        $sede_id = DB::table('sedes')->insertGetId([
            'abreviatura' => 'PRINCIPAL',
            'nombre' => 'SEDE PRINCIPAL'
        ]);

        $dependencia_id = DB::table('dependencias')->insertGetId([
            'sede_id' => $sede_id,
            'abreviatura' => 'INICIAL',
            'nombre' => 'OFICINA INICIAL'
        ]);

        $persona_id = DB::table('personas')->insertGetId([
            'identidad_documento_id' => $identidad_id,
            'nro_documento' => '45233160',
            'nombre' => 'JOSE',
            'apaterno' => 'CORTIJO',
            'amaterno' => 'BELLIDO',            
            'correo' => 'josecortijo2000@hotmail.com',
            'telefono' => '941953770',
            'direccion' => 'Av. 15 de Agosto #836',
            'nacimiento' => '1988-07-08'
        ]);

        DB::table('empleados')->insert([
            'dependencia_id' => $dependencia_id,
            'persona_id' => $persona_id,
            'cargo' => 'PROGRAMADOR',
            'fecha_inicio' => '2022-01-01'
        ]);

        $rol_id = DB::table('roles')->insertGetId([
            'nombre' => 'PROGRAMADOR',
            'descripcion' => 'PROGRAMADOR'
        ]);

        $user_id = DB::table('users')->insertGetId([
            'rol_id' => $rol_id,
            'persona_id' => $persona_id,
            'identidad_documento_id' => $identidad_id,
            'nro_documento' => '45233160',
            'nombre' => 'JOSE',
            'apaterno' => 'CORTIJO',
            'amaterno' => 'BELLIDO',            
            'email' => 'josecortijo2000@hotmail.com',
            'password' => bcrypt('dedosacuatro')
        ]);



    }
}

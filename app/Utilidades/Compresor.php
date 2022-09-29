<?php

namespace App\Utilidades;
use stdClass;

class Compresor
{
    protected $exe_path;
    protected $zip_path;
    protected $directory_path;//destino
    protected $archivos;    

    public function __construct()
    {
        $this->exe_path = config('app.7zip_path'); 
        $this->zip_path = '';
        $this->archivos = array();       
    }

    public function setRuta($new_ruta)
    {
        $this->zip_path = $new_ruta;
    }

    public function setDestino($ruta)
    {
        $this->directory_path = $ruta;
    }

    public function addArchivo($new_archivo)
    {
        $this->archivos[] = $new_archivo;
    }

    public function comprimir()
    {
        $comando = '"'.$this->exe_path.'" a -t7z "'.$this->zip_path.'"';
        foreach ($this->archivos as $archivo) {
            $comando .= ' "'.$archivo.'"';
        }
        //error_log($comando);
        $output = null;
        $result = null;
        exec($comando, $output, $result);
        if($result > 0){
            return false;
        } else {
            return true;
        }
    }

    public function descomprimir()
    {
        $comando = '"'.$this->exe_path.'" e "'.$this->zip_path.'" -o"'.$this->directory_path.'"*';
        $output = null;
        $result = null;
        exec($comando, $output, $result);
        if($result > 0){
            return false;
        } else {
            return true;
        }
    }
}
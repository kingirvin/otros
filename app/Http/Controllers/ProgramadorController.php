<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilidades\Recursos;
use Carbon\Carbon;
use App\Models\Archivo;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProgramadorController extends Controller
{
    public function codigo(Request $request, $id)
    {
        //echo $this->toPoints(841.919)."<br>";
        $link = "https://sgd.unamad.edu.pe/verificar";
        QrCode::size(200)->format('png')->generate($link, public_path().'/img/qrcodes/qrcode.png');

        $archivo = Archivo::find($id);
        /*$parser = new Parser();
        $disco = config('app.almacenamiento');
        $ruta = Storage::disk($disco)->path($archivo->ruta);
        $pdf = $parser->parseFile($ruta);
        $pages = $pdf->getPages();
        $page = $pages[0];
        $dataTm = $page->getDataTm();

        $details = $page->getDetails();
        //print_r($details);
        print_r($dataTm);*/
        $recursos = new Recursos;

        //$ress = $recursos->obtener_info($archivo);
        //print_r($ress);
        return "<br>";



        //$codigo = str_pad(strtoupper(base_convert($id,10,36)), 4, "0", STR_PAD_LEFT);

        /*for ($i=1; $i <= 20000; $i++) { 
            echo "numero: ".$i." - ";
            $codigo = $this->generar($i);
            echo "codigo: ".$codigo."<br>";
        }    
        return "final ";*/
        
        /*$codigo = str_pad($this->generar($id), 4, "0", STR_PAD_LEFT);
        return view('prueba', compact('codigo')); */

        //$date = Carbon::parse('2010-01-01 00:00:00.1');
        //echo $date->milliseconds . "<br>";
        //$ahora = Carbon::now();
        //echo $ahora->milliseconds . "<br>";
        //Get the difference in milliseconds rounded down.
        //echo $date->diffInMilliseconds($ahora). "<br>";

        //echo $date->diffAsCarbonInterval($ahora)->total('milliseconds'). "<br>";

        //$milliseconds = intval(microtime(true) * 1000);

        //echo Carbon::now()->toDateTimeLocalString('millisecond');

/*

        $inicio = Carbon::parse("2007-03-24");
        $ahora = Carbon::now();
        $intervalo = $inicio->diff($ahora);

        print_r($intervalo);*/
        
        /*$version = 0;
        $milisegundos = str_pad($date->diffInMilliseconds($ahora), 12, "0", STR_PAD_LEFT);
        $sufijo = str_pad(rand(0,99), 2, "0", STR_PAD_LEFT);
        $luhn = $this->luhn($version.$milisegundos.$sufijo);

        $todo = $version.$milisegundos.$sufijo.$luhn;
        echo $todo."<br>";
        echo substr($todo,0,4).' '.substr($todo,4,4).' '.substr($todo,8,4).' '.substr($todo,12,4);

*/
        /*$recursos = new Recursos;
        echo $recursos->codigo_cvd();
        return "";*/

        return view('prueba', compact('codigo')); 

    }    
   

    public function generar($numero)
    {
        $alphabet = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";//32
        $largo = strlen($alphabet);

        if($numero <= $largo) {
            $codigo = substr($alphabet,($numero-1),1);
            return $codigo;
        }
        else {
            $factor = floor($numero / $largo);
            $sobrante = $numero - ($largo * $factor);            
            $codigo = substr($alphabet,($sobrante-1),1);
            if($sobrante == 0) { $factor--; }
            return $this->generar($factor).$codigo;
        }        
    }

    
}

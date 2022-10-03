<?php

namespace App\Utilidades;
use Carbon\Carbon;
use setasign\Fpdi\Fpdi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;
use Org_Heigl\Ghostscript\Ghostscript;
use Smalot\PdfParser\Parser;

class Recursos
{
    protected $disco;  
    public $firma_dimenciones;  

    public function __construct()
    {
        $this->disco = config('app.almacenamiento');
        $this->firma_dimenciones = [
            array('height' => 22, 'width' => 62),//0 - Sello + Descripción Horizontal
            array('height' => 42, 'width' => 40),//1 - Sello + Descripción vertical
            array('height' => 22, 'width' => 22),//2 - Solo sello
            array('height' => 22, 'width' => 40)//3 - Solo Descripción
        ];
    }
 
    /**
     * BYTES -> KB, MB, GB
     */
    public function bytes_format($size)
    {
        if ($size >= 1073741824)
        {
            return number_format($size / 1073741824, 2) . ' GB';
        }
        elseif ($size >= 1048576)
        {
            return number_format($size / 1048576, 2) . ' MB';
        }
        elseif ($size >= 1024)
        {
            return number_format($size / 1024, 2) . ' KB';
        }
        elseif ($size > 1)
        {
            return $size.' bytes';
        }
        elseif ($size == 1)
        {
            return $size.' byte';
        }
        else
        {
            return '0 bytes';
        }
    }

    /**
     * GENERAR CODIGO ALPHANUMERICO
     */
    public function codigo_alpha($numero)
    {
        return str_pad($this->generar($numero), 8, "0", STR_PAD_LEFT);
    }
    
    public function generar($numero)//iterativo
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

    /**
     * GENERAR CODIGO PCM
     */
    public function codigo_cvd()
    {
        $inicio = Carbon::parse('2021-01-01 00:00:00.0');
        $ahora = Carbon::now();

        $version = 0;//DIRECTIVA Nº 002-2021-PCM/SGTD
        $milisegundos = str_pad($inicio->diffInMilliseconds($ahora), 12, "0", STR_PAD_LEFT);
        $sufijo = str_pad(rand(0,99), 2, "0", STR_PAD_LEFT);
        $luhn = $this->luhn($version.$milisegundos.$sufijo);
        $todo = $version.$milisegundos.$sufijo.$luhn;
        return $todo;
        //return substr($todo,0,4).' '.substr($todo,4,4).' '.substr($todo,8,4).' '.substr($todo,12,4);
    }

    public function luhn($value)
    {
        $length = strlen($value);
        $parity = $length % 2;
        $sum = 0;

        for ($i = $length - 1; $i >= 0; --$i) {
            // Extract a character from the value.
            $char = $value[$i];
            if ($i % 2 != $parity) {
                $char *= 2;
                if ($char > 9) {
                    $char -= 9;  
                }              
            }
            // Add the character to the sum of characters.
            $sum += $char;
        }
        // Return the value of the sum multiplied by 9 and then modulus 10.
        return ($sum * 9) % 10;
    }

    public function incrustar_codigo($archivo)
    {        
        $ahora = Carbon::now();     
        $link = "sgd.unamad.edu.pe/validar";  
        $texto = utf8_decode("Esta es una representación impresa de un documento electrónico archivado en la UNAMAD, según Decreto Legislativo Nº 1412, Decreto Supremo Nº 029-2021-PCM y la Directiva Nº 002-2021-PCM/SGTD, su autenticidad puede ser contrastada con su versión digital en la siguiente dirección web.");
        $codigo = substr($archivo->cvd,0,4).' '.substr($archivo->cvd,4,4).' '.substr($archivo->cvd,8,4).' '.substr($archivo->cvd,12,4);
        
        $resultado = false;        
        //si existe el archivo
        if(Storage::disk($this->disco)->exists($archivo->ruta))
        {
            $ruta = Storage::disk($this->disco)->path($archivo->ruta);
            $pdf = new Fpdi();
            $paginas = $pdf->setSourceFile($ruta);            
            //insertamos codigo de verficiacion
            for ($i = 1; $i <= $paginas; $i++) { 
                $tpl = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);//obtenemos las dimenciones de la pagina
                $pdf->addPage();//agrega pagina en blanco
                $pdf->useTemplate($tpl, 1, 1, null, null, true);//usa como template la pagina del pdf cargado
                $pdf->SetAutoPageBreak(false);
                //colocamos el qr
                $pdf->SetY(0);
                $pdf->Image(public_path().'/img/qrcodes/qrcode.png', 7, ($size['height'] - 25), 17, 17, 'png');
                //colocamos el texto
                $pdf->SetLeftMargin(27);
                $pdf->SetY(-25);
                $pdf->SetTextColor(110);  
                $pdf->SetFont('Arial','',8);
                $pdf->MultiCell(90,2.7,$texto,0,'J');//95 = ancho A4 / 2 - margen 10
                //colocamos URL (90)
                $pdf->Ln(1);
                $pdf->SetFont('Arial','',8);
                $pdf->Cell(8,3,'URL: ');
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(44,3,$link);
                //colocamos CVD        
                $pdf->SetFont('Arial','',8);
                $pdf->Cell(8,3,'CVD: ');
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(40,3, $codigo);
                //colocamos logo bicentenario                  
                if($size['width'] >= 205) {//A4
                    $pdf->SetY(0);
                    $pdf->Image(public_path().'/img/siempre_pueblo.png', ($size['width'] - 90), ($size['height'] - 22), 37, 13, 'png');
                    $pdf->Image(public_path().'/img/bicentenario.png', ($size['width'] - 51), ($size['height'] - 25), 45, 17, 'png');
                }               
            }            
            
            $pdf->Output("F", $ruta);
            $resultado = true;
        } 
        return $resultado;        
    }

    //incrustar_codigo_certificado
    public function incrustar_codigo_certificado($archivo)
    {
        $ahora = Carbon::now();     
        $link = "sgd.unamad.edu.pe/constancias";  
        $texto = utf8_decode("Revise su autenticidad en $link o escaneado el código QR.");                
        $resultado = false;      
        //si existe el archivo
        if(Storage::disk($this->disco)->exists($archivo->ruta))
        {
            $ruta = Storage::disk($this->disco)->path($archivo->ruta);
            $pdf = new Fpdi();
            $paginas = $pdf->setSourceFile($ruta);            
            //insertamos codigo de verficiacion
            for ($i = 1; $i <= $paginas; $i++) {         
                $tpl = $pdf->importPage($i);//primera pagina
                $size = $pdf->getTemplateSize($tpl);//obtenemos las dimenciones de la pagina
                $pdf->addPage();//agrega pagina en blanco
                $pdf->useTemplate($tpl, 1, 1, null, null, true);//usa como template la pagina del pdf cargado
                $pdf->SetAutoPageBreak(false);
                //solo la primera pagina
                if($i == 1){
                    //colocamos el qr
                    $pdf->SetY(0);
                    $pdf->Image(public_path().'/img/qrcodes/qrcode_const.png', 16, ($size['height'] - 47), 25, 25, 'png');
                    //colocamos el texto
                    $pdf->SetLeftMargin(7);
                    $pdf->SetY(-20);
                    $pdf->SetTextColor(0);  
                    $pdf->SetFont('Arial','',8);
                    $pdf->SetFillColor(255);
                    $pdf->MultiCell(43,3,$texto,0,'J',true);
                    $pdf->Ln(1.5);
                    $pdf->SetFont('Arial','',8);
                    $pdf->Cell(13.5,3,utf8_decode('CÓDIGO: '),0,0,'L',true);
                    $pdf->SetFont('Arial','B',8);
                    $pdf->Cell(28,3, 'C-'.$archivo->codigo,0,0,'L',true); 
                }
            }                 
            
            $pdf->Output("F", $ruta);
            $resultado = true;
        } 

        return $resultado; 
    }

    /**
     * OBTENER DATOS DE PAGINAS Y MINITURAS
     */
    public function datos_firma($archivo)
    { 
        Ghostscript::setGsPath(config('app.ghostscript_path'));
        $gs = new Ghostscript();

        $ruta = Storage::disk($this->disco)->path($archivo->ruta);
        $pdf_fpdi = new Fpdi();
        $gs->setDevice('jpeg');
        $gs->setInputFile($ruta);
        //$gs->setResolution(96);
        //$gs->setTextAntiAliasing(Ghostscript::ANTIALIASING_HIGH);
        //$gs->getDevice()->setQuality(100);

        $paginas = $pdf_fpdi->setSourceFile($ruta);
        $carpeta_publica = public_path();
        $nombre_solo = substr(basename($ruta), 0, strrpos(basename($ruta), '.'));
        $datos = array();
        //pagina inicial
        $tpl_i = $pdf_fpdi->importPage(1);
        $size_i = $pdf_fpdi->getTemplateSize($tpl_i);
        $pagina_i = array();
        $pagina_i["width"] = $size_i["width"];
        $pagina_i["height"] = $size_i["height"];
        $pagina_i["orientation"] = $size_i["orientation"];        
        $temp_i = '/temp/'.$nombre_solo.'_mini.jpg';
        $gs->setOutputFile($carpeta_publica.$temp_i);
        $gs->setPageStart(1);
        $gs->setPageEnd(1);
        $gs->render(); 
        $pagina_i["miniatura"] = $temp_i;
        $datos[] = $pagina_i;    
        //pagina final
        if($paginas > 1){
            $tpl_f = $pdf_fpdi->importPage($paginas);
            $size_f = $pdf_fpdi->getTemplateSize($tpl_f);
            $pagina_f = array();
            $pagina_f["width"] = $size_f["width"];
            $pagina_f["height"] = $size_f["height"];
            $pagina_f["orientation"] = $size_f["orientation"];
            $temp_f = '/temp/'.$nombre_solo.'_minf.jpg';
            $gs->setOutputFile($carpeta_publica.$temp_f);
            $gs->setPageStart($paginas);
            $gs->setPageEnd($paginas);
            $gs->render();             
            $pagina_f["miniatura"] = $temp_f;
            $datos[] = $pagina_f;  
        }
        return json_encode($datos);
    }

    /**
     * OBTIENE COORDENADAS X,Y y NUMERO DE PAGINA A FIRMAR
     */
    public function obtener_pagina($archivo, $ubicacion, $exacto, $posicion, $apariencia)
    {       
        //obtenemos la ruta completa
        $ruta = Storage::disk($this->disco)->path($archivo->ruta);        
        $pdf = new Fpdi();
        $paginas = $pdf->setSourceFile($ruta);
        //obtenemos el index de la pagina
        $pos_index = ( $ubicacion == 0 ? 0 : $paginas - 1 );//0->0, 1->n
        $tpl = $pdf->importPage($pos_index + 1);
        $size = $pdf->getTemplateSize($tpl);
        //obtenemos la posicion donde estar la firma
        $height_firma = $this->firma_dimenciones[$apariencia]["height"];
        $width_firma = $this->firma_dimenciones[$apariencia]["width"];  

        if($exacto == 1){//en la posicion exacta
            $posiciones = explode("-", $posicion);
            $pos_x = $posiciones[1];//(mm)
            $pos_y = $posiciones[0];//(mm)
        } else {//relativo
            //obtenemos las posiciones [6 x 3]
            $posiciones = explode("-", $posicion);
            $margen = 5;
            $height_seccion = $size['height'] / 5; //(mm)

            //calculamos x
            if($posiciones[1] == 1) 
                $pos_x = $margen;        
            elseif ($posiciones[1] == 2)
                $pos_x = ($size['width'] / 2) - ($width_firma / 2);
            else 
                $pos_x = $size['width'] - $width_firma - $margen;
            
            //calculamos y
            if($posiciones[0] == 1) 
                $pos_y = $margen;
            elseif($posiciones[0] == 2) 
                $pos_y = ($height_seccion * 2) - ($height_seccion/2) - ($height_firma/2);
            elseif($posiciones[0] == 3) 
                $pos_y = ($height_seccion * 3) - ($height_seccion/2) - ($height_firma/2);   
            elseif($posiciones[0] == 4)            
                $pos_y = ($height_seccion * 4) - ($height_seccion/2) - ($height_firma/2);        
            else
                $pos_y = $size['height'] - $height_firma - $margen;
        }
        
        return array('pagina' => $pos_index, 'x' => $this->toPoints($pos_x), 'y' => $this->toPoints($pos_y));                 
    }

    protected function toPoints($mm)//CONVIERTE DE LAS COORDENDAS DE MILIMETROS A PONTS
    {
        //1 inch = 72 points
        //1 inch = 25.4 mm
        //1 point = 0.352777778 mm
        $factor = 0.352777778;
        return round($mm / $factor);
    }
  


    /*
    public function obtener_info($archivo)
    {
        $ruta = Storage::disk($this->disco)->path($archivo->ruta);       
        //setasign\Fpdi
        $pdf_fpdi = new Fpdi();
        $paginas = $pdf_fpdi->setSourceFile($ruta);    
        //Smalot\PdfParser
        $parser = new Parser();
        $pdf_parser = $parser->parseFile($ruta);
        $parser_pages = $pdf_parser->getPages();        
        $datos = array();

        for ($i=0; $i < $paginas ; $i++) { 
            $tpl = $pdf_fpdi->importPage($i + 1);
            $size = $pdf_fpdi->getTemplateSize($tpl);
            //setasign\Fpdi
            $pagina = array();
            $pagina["width"] = $size["width"];
            $pagina["height"] = $size["height"];
            $pagina["orientation"] = $size["orientation"];
            //Smalot\PdfParser
            $page = $parser_pages[$i];
            $details = $page->getDetails();
            $pagina["width_px"] = $details["MediaBox"][2];
            $pagina["height_px"] = $details["MediaBox"][3];
            $dataTm = $page->getDataTm();
            $pagina["firmas"] = $this->obtener_posiciones($dataTm);   

            $datos[] = $pagina;    
        }

        return json_encode($datos);        
    }
    */

    

/*
    public function obtener_posiciones($arreglo)
    {
        $resultado = array();
        foreach ($arreglo as $dato) {
            if(strpos($dato[1], '<f>') !== false){
                $resultado[] = array(
                    "x" => $dato[0][4],
                    "y" => $dato[0][5]
                );
            }
        }
        return $resultado;
    }*/
 
  

    /**
     * OBTIENE LA UBICACION (X,Y) Y N° PAGINA A FIRMAR
     * ubicacion = 1:primera, 0:ultima pagina
     * posicion = 1-1, 1-2, 1-3, 2-1, ..., 5-3
     * apariencia = 0:sello + Descripción horizontal, 1: sello + Descripción Vertical
     */
    
    
    
}
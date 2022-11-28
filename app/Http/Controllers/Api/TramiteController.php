<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Utilidades\Recursos;
use Validator;
use Carbon\Carbon;
use App\Models\Tramite;
use App\Models\Documento;
use App\Models\Documento_anexo;
use App\Models\Movimiento;
use App\Models\Dependencia;
use App\Models\Empleado;
use App\Models\Movimiento_observacion;
use stdClass;
use PDF;
use DataTables;

class TramiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');        
    }

    /**
     * INICIAR NUEVO TRAMITE
     */
    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'o_dependencia_id' => 'required',
            'o_empleado_id' => 'required',
            'o_persona_id' => 'required',
            'destinos' => 'required',
            'documento_tipo_id' => 'required', 
            'numero' => 'required', 
            'remitente' => 'required', 
            'folios' => 'required', 
            'asunto' => 'required', 
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $ahora = Carbon::now(); 
            $user = Auth::user();
            $recursos = new Recursos;
            $o_dependencia = Dependencia::find($request->o_dependencia_id);

            //TRAMIE
            $tramite = new Tramite;
            $tramite->year = $ahora->year;
            $tramite->o_tipo = 0;//0:interno
            $tramite->o_dependencia_id = $request->o_dependencia_id;
            $tramite->o_user_id = $user->id;
            if($request->has('procedimiento_id')){
                $tramite->procedimiento_id = ($request->procedimiento_id != 0 ? $request->procedimiento_id : null);
            }            
            $tramite->observaciones = $request->asunto;
            $tramite->user_id = $user->id;
            $tramite->estado = 1;//1:activo

            if (!$tramite->save()) {
                return response()->json(['message'=>'No se pudo registrar el trámite'], 500);
            }

            $tramite->codigo = $recursos->codigo_alpha($tramite->id);
            $tramite->save();
                    
            $ultimo_emitido = Documento::where('dependencia_id', $request->o_dependencia_id)
                                ->where('year', $ahora->year)
                                ->orderBy('o_numero', 'desc')
                                ->first();

            //DOCUMENTO
            $documento = new Documento;
            $documento->year = $ahora->year;
            $documento->tramite_id = $tramite->id;
            $documento->dependencia_id = $request->o_dependencia_id;
            $documento->empleado_id = $request->o_empleado_id;
            $documento->persona_id = $request->o_persona_id;
            $documento->o_numero = ($ultimo_emitido ? $ultimo_emitido->o_numero + 1 : 1);
            $documento->documento_tipo_id = $request->documento_tipo_id;
            $documento->numero = $request->numero;
            $documento->remitente = $request->remitente;
            $documento->asunto = $request->asunto;
            $documento->folios = $request->folios;            
            $documento->observaciones = $request->observaciones;            
            $documento->archivo_id = ($request->archivo_id != 0 ? $request->archivo_id : null);
            $documento->user_id = $user->id;

            if (!$documento->save()) {
                return response()->json(['message'=>'No se pudo registrar el documento'], 500);
            }

            $documento->codigo = $recursos->codigo_alpha($documento->id);
            $documento->save();

            //registramos los documentos anexos
            if(isset($request->anexos)) {
                foreach ($request->anexos as $anexo) {
                    if($anexo["id"] != 0) {
                        $documento_anexo = new Documento_anexo;
                        $documento_anexo->documento_id = $documento->id;
                        $documento_anexo->archivo_id = $anexo["id"];
                        $documento_anexo->principal = 1;
                        $documento_anexo->save(); 
                    }
                }
            }
    
            //MOVIMIENTOS            
            foreach ($request->destinos as $destino) {
                $movimiento = new Movimiento;
                $movimiento->tramite_id = $tramite->id;
                $movimiento->documento_id = $documento->id;                
                $movimiento->tipo = 0;//0:inicio (sin accion)
                $movimiento->copia = $destino["como_copia"]; 

                //quien envia
                $movimiento->o_tipo = 0;//0:interno
                $movimiento->o_dependencia_id = $request->o_dependencia_id;
                $movimiento->o_empleado_id = $request->o_empleado_id;
                $movimiento->o_persona_id = $request->o_persona_id;
                $movimiento->o_fecha = $ahora->format('Y-m-d H:i:s');
                $movimiento->o_user_id = $user->id;
                $movimiento->o_year = $ahora->year;
                $movimiento->o_numero = ($ultimo_emitido ? $ultimo_emitido->o_numero + 1 : 1);
                $movimiento->o_descripcion = $o_dependencia->nombre." | ".$request->remitente;
              
                //quien recibe
                $movimiento->d_tipo = $destino["tipo"];//0:interno, 1:externo

                if($destino["tipo"] == 0) //0:interno
                {   
                    $movimiento->d_dependencia_id = $destino["d_dependencia_id"];
                    if($destino["d_empleado_id"] != 0){//dirigido a una persona 
                        $movimiento->d_empleado_id = $destino["d_empleado_id"];  
                        $movimiento->d_persona_id = $destino["d_persona_id"];  
                    }
                }
                else//1:externo
                {
                    $movimiento->d_identidad_documento_id = ($destino["d_identidad_documento_id"] != 0 ? $destino["d_identidad_documento_id"] : null);
                    $movimiento->d_nro_documento = $destino["d_nro_documento"];     
                    $movimiento->d_nombre = $destino["d_nombre"];  	
                }

                $movimiento->estado = 1;//0:anulado, 1:por recepcionar, 2:recepcionado, 3:derivado/referido, 4:atendido, 5:observado     
                $movimiento->save();
            }

            return response()->json(['data'=>$tramite, 'message'=>'Registrado correctamente'], 200);              
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()."- linea: ".__LINE__." ".__FILE__], 500);
        }
    }

    /**
     * DOCUMENTOS EMITIDOS
     */
    public function emitidos(Request $request)
    {
        $year = $request->input('year');
        $dependencia_id = $request->input('dependencia_id');
        $persona_id = $request->input('persona_id');        

        $query = Documento::with(['tramite','documento_tipo','user','movimientos' => function ($query) {
                    $query->with(['d_dependencia','d_persona'])->whereNotNull('o_numero');
                }])
                ->where('dependencia_id', $dependencia_id)
                ->where('year', $year);

        if($persona_id != 0){
            $query->where('persona_id', $persona_id);
        }

        return DataTables::of($query)->toJson();
    }
    
    /**
     * CORREGIR ALGUNOS DATOS DE DOCUMENTO
     */
    public function modificar_documento(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [            
            'documento_tipo_id' => 'required', 
            'numero' => 'required', 
            //'remitente' => 'required', 
            'folios' => 'required', 
            'asunto' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $user = Auth::user();
            $documento = Documento::find($id);

            if($documento)
            {
                if($documento->user_id == $user->id)
                {
                    //el documento tiene movimientos que ya esten recibidos
                    //0:anulado, 1:por recepcionar, 2:recepcionado, 3:derivado/referido, 4:atendido, 5:observado            
                    $movimientos_count = Movimiento::where('documento_id', $documento->id)->where('estado','>',1)->count();

                    if($movimientos_count == 0)
                    {
                        $documento->documento_tipo_id = $request->documento_tipo_id;
                        $documento->numero = $request->numero;
                        //$documento->remitente = $request->remitente;
                        $documento->folios = $request->folios;
                        $documento->asunto = $request->asunto;
                        $documento->observaciones = $request->observaciones;

                        if($documento->save())
                            return response()->json(['message'=>"Actualizado correctamente"], 200);
                        else
                            return response()->json(['message'=>"No se pudo actualizar"], 200);
                    }
                    else
                        return response()->json(['message'=>"No se pude modificar si ya ha sido recepcionado"], 500);  
                }   
                else
                    return response()->json(['message'=>"No eres el autor del documento"], 500);                
            }
            else
                return response()->json(['message'=>"No se pudo encontrar el documento"], 500);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    /**
     * ANULAR EMISION
     */
    public function anular_emision(Request $request, $id)
    {
        $user = Auth::user();
        $documento = Documento::find($id);

        if($documento)
        {
            if($documento->user_id == $user->id)
            {
                $movimientos = Movimiento::where('documento_id', $documento->id)->orderBy('id', 'asc')->get();
                //obtenemos el primer set de movimientos
                if(count($movimientos) > 0)
                {
                    //ya ha sido recepcionado?
                    $recepcionado = false;
                    $continua = false;

                    //0:anulado, 1:por recepcionar, 2:recepcionado, 3:derivado/referido, 4:atendido, 5:observado            
                    foreach ($movimientos as $movimiento) {
                        if($movimiento->estado > 1)//alguno de los movimientos ya ha sido recepcionado
                            $recepcionado = true;

                        if($movimiento->anterior_id != null)//alguno de los movimientos es una continuacion de otro
                            $continua = true;
                    }

                    if(!$recepcionado)
                    {                                           
                        $movimiento_rep = $movimientos[0];//movimiento representativo
                        $tramite = Tramite::find($documento->tramite_id);

                        //eliminamos los movimientos
                        foreach ($movimientos as $movimiento) {
                            $movimiento->delete();
                        }
                        //eliminamos documento
                        $eliminados = Documento_anexo::where('documento_id', $documento->id)->delete();
                        $documento->delete();

                        //es el inicio de un tramite o continuacion?     
                        if(!$continua)                        
                            $tramite->delete();                            
                        
                        return response()->json(['message'=>"Anulado correctamente"], 200);
                    }
                    else
                        return response()->json(['message'=>"El documento ya ha sido recepcionado"], 500);                   
                }
                else
                    return response()->json(['message'=>"No se encontraron movimientos"], 500); 
            }   
            else
                return response()->json(['message'=>"No eres el autor del documento"], 500); 
        }
        else
            return response()->json(['message'=>"No se pudo encontrar el documento"], 500);
    }

    /**
     * DOCUMENTOS POR RECEPCIONAR
     */
    public function por_recepcionar(Request $request)
    {
        $year = $request->input('year');
        $dependencia_id = $request->input('dependencia_id');
        $persona_id = $request->input('persona_id');        
        $user = Auth::user();

        $query = Movimiento::with(['o_dependencia.sede','d_persona','documento.documento_tipo','tramite','o_user','accion'])
                ->where('d_dependencia_id', $dependencia_id)
                ->where('estado', 1)//0:anulado, 1:por recepcionar, 2:recepcionado, 3:derivado/referido, 4:atendido, 5:observado     
                ->whereYear('created_at', $year);

        if($persona_id != -1){
            if($persona_id != 0){
                $query->where('d_persona_id', $persona_id);
            } else {
                $query->whereNull('d_persona_id');
            }
        }

        return DataTables::of($query)->toJson();
    }
    
    /**
     * RECEPCIONAR DOCUMENTO
     */
    public function recepcionar(Request $request, $id)
    {
        try 
        {
            $movimiento = Movimiento::find($id);
            $user = Auth::user();
            $ahora = Carbon::now();
            $input = $request->all();

            if($movimiento == null){
                return response()->json(['message'=>'No se encontro el movimiento'], 500);  
            }

            $empleados_destino = Empleado::where('dependencia_id', $movimiento->d_dependencia_id)->where('persona_id', $user->persona_id)->where('estado',1)->count();
            if($empleados_destino == 0){
                return response()->json(['message'=>'El usuario no pertenece a la dependencia destino'], 500);        
            }
            
            //obtenemos el ultimo movimiento recepcionado de la oficina destino
            $ultimo = Movimiento::where('d_dependencia_id', $movimiento->d_dependencia_id)
            ->where('estado', '>', 1)//0:anulado, 1:por recepcionar, 2:recepcionado, 3:derivado/referido, 4:atendido, 5:observado     
            ->where('d_year', $ahora->year)//de este año
            ->orderBy('d_numero', 'desc')
            ->first();

            $movimiento->d_fecha = $ahora->format('Y-m-d H:i:s');
            $movimiento->d_user_id = $user->id;
            $movimiento->d_observacion = $input["d_observacion"];
            $movimiento->d_year = $ahora->year;
            $movimiento->d_numero = ($ultimo ? $ultimo->d_numero + 1 : 1 );
            $movimiento->estado = 2;//recepcionado

            if($movimiento->save())
                return response()->json(['message'=>'Actualizado correctamente'], 200);  
            else
                return response()->json(['message'=>'No se pudo actualizar'], 500);            
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    /**
     * LISTAR DOCUMENTO INGRESADOS A DEPENDENCIA
     */
    public function recepcionados(Request $request)
    {
        $year = $request->input('year');
        $dependencia_id = $request->input('dependencia_id');
        $persona_id = $request->input('persona_id');   
        $estado = $request->input('estado');

        $query = Movimiento::with(['o_dependencia.sede','d_persona','documento.documento_tipo','tramite','d_user','accion'])
                ->where('d_dependencia_id', $dependencia_id)
                ->whereYear('created_at', $year);

        if($persona_id != -1){
            if($persona_id != 0){
                $query->where('d_persona_id', $persona_id);
            } else {
                $query->whereNull('d_persona_id');
            }
        }
        //0:anulado, 1:por recepcionar, 2:recepcionado, 3:derivado/referido, 4:atendido, 5:observado 
        if($estado != 0){
            $query->where('estado', '=', $estado);
        }else{
            $query->where('estado', '>', 1);
        }

        return DataTables::of($query)->toJson();
    }
    
    /**
     * DERIVAR MOVIMIENTO
     */
    public function derivar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movimiento_id' => 'required',
            'destinos' => 'required',
            'metodo' => 'required',
            //si es adjuntar nuevo documento
            'o_empleado_id' => 'required_if:metodo,1',
            'o_persona_id' => 'required_if:metodo,1',
            'documento_tipo_id' => 'required_if:metodo,1',
            'numero' => 'required_if:metodo,1',
            'remitente' => 'required_if:metodo,1',
            'asunto' => 'required_if:metodo,1',
            'folios' => 'required_if:metodo,1',
            //si es proveido
            'accion_id' => 'required_if:metodo,0',
            'accion_otros' => 'required_if:metodo,0' 
        ]); 
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $ahora = Carbon::now();
            $user = Auth::user();
            $recursos = new Recursos;
            $movimiento = Movimiento::with(['tramite.documentos','documento'])->find($request->movimiento_id);

            if($movimiento == null){
                return response()->json(['message'=>'El registro no es encuentra disponible'], 500);          
            }

            //registramos o seleccionamos el DOCUMENTO
            $documento_id = 0;
            $o_dependencia = Dependencia::find($movimiento->d_dependencia_id);//dependencia destino del anterior movimiento = depencia actual de envio
            $o_numero = null;

            if($request->metodo == 1)//nuevo documento
            {
                //obtenemos el ultimo documento emitido por la dependencia
                $ultimo_emitido = Documento::where('dependencia_id', $o_dependencia->id)
                                ->where('year', $ahora->year)
                                ->orderBy('o_numero', 'desc')
                                ->first();
                $o_numero = ($ultimo_emitido ? $ultimo_emitido->o_numero + 1 : 1);
                //registramos el nuevo documento
                $documento = new Documento;
                $documento->year = $ahora->year;
                $documento->tramite_id = $movimiento->tramite_id;
                $documento->dependencia_id = $o_dependencia->id;
                $documento->empleado_id = $request->o_empleado_id;
                $documento->persona_id = $request->o_persona_id;
                $documento->o_numero = $o_numero;
                $documento->documento_tipo_id = $request->documento_tipo_id;
                $documento->numero = $request->numero;
                $documento->remitente = $request->remitente;
                $documento->asunto = $request->asunto;
                $documento->folios = $request->folios;            
                $documento->observaciones = $request->observaciones;            
                $documento->archivo_id = ($request->archivo_id != 0 ? $request->archivo_id : null);
                $documento->user_id = $user->id;

                if (!$documento->save()) {
                    return response()->json(['message'=>'No se pudo registrar el documento'], 500);
                }

                $documento->codigo = $recursos->codigo_alpha($documento->id);
                $documento->save();

                //registramos los documentos anexos
                if(isset($request->anexos)) {
                    foreach ($request->anexos as $anexo) {
                        if($anexo["id"] != 0) {
                            $documento_anexo = new Documento_anexo;
                            $documento_anexo->documento_id = $documento->id;
                            $documento_anexo->archivo_id = $anexo["id"];
                            $documento_anexo->principal = 1;
                            $documento_anexo->save(); 
                        }
                    }
                }

                $documento_id = $documento->id; 
            } 
            else //proveido
            {
                $documento_id = $movimiento->documento_id;
            }

            //registramos el MOVIMIENTO
            foreach ($request->destinos as $destino) {
                $movimiento_nuevo = new Movimiento;
                $movimiento_nuevo->tramite_id = $movimiento->tramite_id;
                $movimiento_nuevo->documento_id = $documento_id;
                //metodo de envio
                if($request->metodo == 0) {//proveido
                    $movimiento_nuevo->accion_id = $request->accion_id;
                    $movimiento_nuevo->accion_otros = $request->accion_otros;   
                    $movimiento_nuevo->tipo = 1;//1:derivacion (proveido)
                } else {//nuevo documento
                    $movimiento_nuevo->tipo = 2;//2:referido (derivado con nuevo documento)
                    $movimiento_nuevo->o_empleado_id = $request->o_empleado_id;
                    $movimiento_nuevo->o_persona_id = $request->o_persona_id;
                }
                $movimiento_nuevo->anterior_id = $movimiento->id;
                $movimiento_nuevo->copia = $destino["como_copia"];
                //quien envia
                $movimiento_nuevo->o_tipo = 0;//0:interno
                $movimiento_nuevo->o_dependencia_id = $o_dependencia->id;
                $movimiento_nuevo->o_fecha = $ahora->format('Y-m-d H:i:s');
                $movimiento_nuevo->o_user_id = $user->id;
                $movimiento_nuevo->o_year = $ahora->year;
                $movimiento_nuevo->o_numero = $o_numero;
                $movimiento_nuevo->o_descripcion = $o_dependencia->nombre;
                //quien recibe      
                $movimiento_nuevo->d_tipo = $destino["tipo"];//0:interno, 1:externo

                if($destino["tipo"] == 0) //0:interno
                {   
                    $movimiento_nuevo->d_dependencia_id = $destino["d_dependencia_id"];  
                    if($destino["d_empleado_id"] != 0){//dirigido a una persona 
                        $movimiento_nuevo->d_empleado_id = $destino["d_empleado_id"];  
                        $movimiento_nuevo->d_persona_id = $destino["d_persona_id"];  
                    }
                }
                else
                {
                    $movimiento_nuevo->d_identidad_documento_id = ($destino["d_identidad_documento_id"] != 0 ? $destino["d_identidad_documento_id"] : null);
                    $movimiento_nuevo->d_nro_documento = $destino["d_nro_documento"];
                    $movimiento_nuevo->d_nombre = $destino["d_nombre"];
                }

                $movimiento_nuevo->estado = 1;//1:por recepcionar,
                $movimiento_nuevo->save(); 
            }      

            $movimiento->estado = 3;//3:derivado/referido
            $movimiento->save();           

            return response()->json(['message'=>'Registrado correctamente'], 200);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()."- linea: ".__LINE__." ".__FILE__], 500);
        }
    }

    /**
     * ANULAR DERIVACIONES
     */
    public function anular_derivacion(Request $request, $id)
    {
        $user = Auth::user();
        $movimiento = Movimiento::find($id);

        if($movimiento)
        {
            if($movimiento->o_user_id == $user->id)
            {
                $documento_id = $movimiento->documento_id;
                $anterior_id = $movimiento->anterior_id;
                //eliminamos el movimiento
                $movimiento->delete();
                //si el documento ya no le quedan movimientos lo elimianmos
                $documento = Documento::withCount('movimientos')->find($documento_id);                
                if($documento->movimientos_count == 0) {     
                    //eliminamos documento
                    $eliminados = Documento_anexo::where('documento_id', $documento->id)->delete();
                    $documento->delete();      
                }
                //si el movimiento anterior ya no le quedan movimientos lo cambiamos a estado pendiente
                $movimiento_anterior = Movimiento::withCount('siguientes')->find($anterior_id);                 
                if($movimiento_anterior->siguientes_count == 0) {             
                    $movimiento_anterior->estado = 2;//2:recepcionado
                    $movimiento_anterior->save();
                }                  

                //no se anula tramite ya que se sobreentiende que existe un paso previo
                return response()->json(['message'=>"Anulado correctamente"], 200);
            }
            else
                return response()->json(['message'=>"No eres el autor de la derivacion"], 500); 
        }
        else
            return response()->json(['message'=>"No se pudo encontrar el movimiento"], 500);
    }

    /**
     * MARCAR COMO ATENDIDO
     */
    public function atender(Request $request, $id)
    {
        $user = Auth::user();
        $movimiento = Movimiento::find($id);
        $ahora = Carbon::now();        

        if($movimiento)
        {
            if($movimiento->estado == 2)//2:recepcionado
            {                
                $movimiento->f_user_id = $user->id;
                $movimiento->f_fecha = $ahora->format('Y-m-d H:i:s');
                $movimiento->f_observacion = $request->f_observacion;
                $movimiento->estado = 4;//4:atendido

                if($movimiento->save())
                    return response()->json(['message'=>'Actualizado correctamente'], 200);  
                else
                    return response()->json(['message'=>'No se pudo actualizar'], 500);                
            }
            else
                return response()->json(['message'=>"El movimiento debe estar en estado pendiente"], 500);
        }
        else
            return response()->json(['message'=>"No se pudo encontrar el movimiento"], 500);
    }
    
    /**
     * DESMARCAR EL ESTADOA ATENDIDO
     */
    public function anular_atendido(Request $request, $id)
    {
        $user = Auth::user();
        $movimiento = Movimiento::find($id);

        if($movimiento)
        {     
            if($movimiento->estado == 4)//4:atendido
            {                       
                $movimiento->f_user_id = null;
                $movimiento->f_fecha = null;
                $movimiento->f_observacion = null;
                $movimiento->estado = 2;//2:recepcionado

                if($movimiento->save())
                    return response()->json(['message'=>'Actualizado correctamente'], 200);  
                else
                    return response()->json(['message'=>'No se pudo actualizar'], 500);
            }
            else
                return response()->json(['message'=>"El movimiento debe estar en estado atendido"], 500);
        }
        else
            return response()->json(['message'=>"No se pudo encontrar el movimiento"], 500);
    }

    /**
     * ANULAR INGRESO A OFICINA
     */
    public function anular_recepcion(Request $request, $id)
    {
        $user = Auth::user();
        $movimiento = Movimiento::find($id);       

        if($movimiento)
        {            
            if($movimiento->estado == 2)//2:recepcionado
            {
                //no debe tener derivaciones
                $derivaciones_count = Movimiento::where('anterior_id', $movimiento->id)->count();

                if($derivaciones_count == 0)//recepcionado
                {
                    $movimiento->d_fecha = null;
                    $movimiento->d_user_id = null;
                    $movimiento->d_observacion = null;
                    $movimiento->d_year = null;
                    $movimiento->d_numero = null;
                    $movimiento->estado = 1;//1:por recepcionar

                    if($movimiento->save())
                        return response()->json(['message'=>'Actualizado correctamente'], 200);  
                    else
                        return response()->json(['message'=>'No se pudo actualizar'], 500);
                }
                else
                    return response()->json(['message'=>"El documento tiene registrado derivaciones"], 500);
            }
            else
                return response()->json(['message'=>"El movimiento debe estar en estado pendiente"], 500);
        }
        else
            return response()->json(['message'=>"No se pudo encontrar el movimiento"], 500);
    }

    /**
     * REGISTRAR OBSERVACION EN UN MOVIMIENTO
     */
    public function observar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [            
            'detalle' => 'required',
        ]);    

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $movimiento = Movimiento::find($id);            
            $user = Auth::user();           

            if($movimiento)
            {
                $tramite = Tramite::find($movimiento->tramite_id);

                if($movimiento->estado == 2)//2:recepcionado
                { 
                    $observacion = new Movimiento_observacion;
                    $observacion->tramite_id = $tramite->id;
                    $observacion->movimiento_id = $movimiento->id;
                    $observacion->user_id = $user->id;
                    $observacion->detalle = $request->detalle;

                    if($observacion->save()){
                        $movimiento->estado = 5;//5:observado 
                        $movimiento->save();
                        $tramite->estado = 2;//2:observado
                        $tramite->save();

                        return response()->json(['message'=>'Registrado correctamente'], 200); 
                    } 
                    else
                        return response()->json(['message'=>'No se pudo registrar'], 500);
                }
                else
                    return response()->json(['message'=>"El movimiento debe estar en estado pendiente"], 500);
            }
            else
                return response()->json(['message'=>"No se pudo encontrar el movimiento"], 500);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    /**
     * LISTA OBSERVACIONES DE UN MOVIMIENTO
     */
    public function observaciones(Request $request, $id)
    {
        $observaciones = Movimiento_observacion::where('movimiento_id',$id)->get();
        return response()->json(['data'=>$observaciones], 200);
    }

    /**
     * ANULA UNA OBSERVACION (SI NO HAY MAS OBSERVACIONES CAMBIA ESTADO)
     */
    public function anular_observacion(Request $request, $id)
    {
        $observacion = Movimiento_observacion::find($id);

        if($observacion)
        {
            if($observacion->delete()){
                $movimiento = Movimiento::withCount('observaciones')->find($observacion->movimiento_id);
                if($movimiento->observaciones_count == 0){
                    $movimiento->estado = 2;//2:recepcionado,
                    $movimiento->save();
                }
                $tramite = Tramite::withCount('observaciones')->find($observacion->tramite_id);
                if($tramite->observaciones_count == 0){
                    $tramite->estado = 1;//1:activo
                    $tramite->save();
                }

                return response()->json(['message'=>'Eliminado correctamente'], 200);  
            }
            else
                return response()->json(['message'=>'No se pudo eliminar'], 500);
        }
        else
            return response()->json(['message'=>"No se pudo encontrar la observación"], 500);
    }

    public function recepcionar_externo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'documento_tipo_id' => 'required', 
            'numero' => 'required', 
            'remitente' => 'required', 
            'folios' => 'required', 
            'asunto' => 'required', 
            'tipo_persona' => 'required', 
            'razon_social' => 'required_if:tipo_persona,1',
            'nombre' => ['required'],
            'apaterno' => ['required'],
            'amaterno' => ['required'],
            'd_dependencia_id' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $ahora = Carbon::now(); 
            $user = Auth::user();
            $recursos = new Recursos;
            $d_dependencia = Dependencia::find($request->d_dependencia_id);

            //TRAMIE
            $tramite = new Tramite;
            $tramite->year = $ahora->year;
            $tramite->o_tipo = 1;//1:externo
            $tramite->o_externo_tipo = 0;//0:persona externa si usuario
            
            if($request->tipo_persona == 1)//1:Persona Jurídica
            {
                $tramite->ruc = $request->ruc;
                $tramite->razon_social = $request->razon_social;
            }

            $tramite->o_identidad_documento_id = ($request->identidad_documento_id != 0 ? $request->identidad_documento_id : null);
            $tramite->o_nro_documento = $request->nro_documento;
            $tramite->o_nombre = $request->nombre;
            $tramite->o_apaterno = $request->apaterno;
            $tramite->o_amaterno = $request->amaterno;
            $tramite->o_telefono = $request->telefono;
            $tramite->o_correo = $request->email;
            $tramite->o_direccion = $request->direccion;

            if($request->has('procedimiento_id')){
                $tramite->procedimiento_id = ($request->procedimiento_id != 0 ? $request->procedimiento_id : null);
            }   

            $tramite->observaciones = $request->asunto;
            $tramite->o_user_id = $user->id;
            $tramite->user_id = $user->id;
            $tramite->estado = 1;//1:activo

            if (!$tramite->save()) {
                return response()->json(['message'=>'No se pudo registrar el trámite'], 500);
            }

            $tramite->codigo = $recursos->codigo_alpha($tramite->id);
            $tramite->save();

            //DOCUMENTO
            $documento = new Documento;
            $documento->year = $ahora->year;
            $documento->tramite_id = $tramite->id;
            $documento->documento_tipo_id = $request->documento_tipo_id;
            $documento->numero = $request->numero;
            $documento->remitente = $request->remitente;
            $documento->asunto = $request->asunto;
            $documento->folios = $request->folios;            
            $documento->observaciones = $request->observaciones;            
            $documento->archivo_id = ($request->archivo_id != 0 ? $request->archivo_id : null);
            $documento->user_id = $user->id;

            if (!$documento->save()) {
                return response()->json(['message'=>'No se pudo registrar el documento'], 500);
            }

            $documento->codigo = $recursos->codigo_alpha($documento->id);
            $documento->save();

            //registramos los documentos anexos
            if(isset($request->anexos)) {
                foreach ($request->anexos as $anexo) {
                    if($anexo["id"] != 0) {
                        $documento_anexo = new Documento_anexo;
                        $documento_anexo->documento_id = $documento->id;
                        $documento_anexo->archivo_id = $anexo["id"];
                        $documento_anexo->principal = 1;
                        $documento_anexo->save(); 
                    }
                }
            }

            //MOVIMIENTO
            $movimiento = new Movimiento;
            $movimiento->tramite_id = $tramite->id;
            $movimiento->documento_id = $documento->id;                
            $movimiento->tipo = 0;//0:inicio de trámite
            $movimiento->copia = 0;
            //quien envia
            $movimiento->o_tipo = 1;//1:externo  

            if($request->tipo_persona == 1){//1:Persona Jurídica            
                $movimiento->o_descripcion = $request->ruc." | ".$request->razon_social;
            } else {
                $movimiento->o_descripcion = $request->nro_documento." | ".$request->nombre." ".$request->apaterno." ".$request->amaterno;
            }

            $movimiento->o_fecha = $ahora->format('Y-m-d H:i:s');
            $movimiento->o_user_id = $user->id;
            $movimiento->o_year = $ahora->year;
            
            //quien recibe
            $movimiento->d_tipo = 0;//0:interno
            $movimiento->d_dependencia_id = $request->d_dependencia_id;            
            if($request->d_empleado_id != 0){
                $movimiento->d_empleado_id = $request->d_empleado_id;  
                $movimiento->d_persona_id = $request->d_persona_id;  
            }

            //obtenemos el ultimo movimiento recepcionado de la oficina destino
            $ultimo = Movimiento::where('d_dependencia_id', $request->d_dependencia_id)
            ->where('estado', '>', 1)//0:anulado, 1:por recepcionar, 2:recepcionado, 3:derivado/referido, 4:atendido, 5:observado     
            ->where('d_year', $ahora->year)//de este año
            ->orderBy('d_numero', 'desc')
            ->first();

            $movimiento->d_fecha = $ahora->format('Y-m-d H:i:s');
            $movimiento->d_user_id = $user->id;
            $movimiento->d_observacion = $request->observaciones;
            $movimiento->d_year = $ahora->year;
            $movimiento->d_numero = ($ultimo ? $ultimo->d_numero + 1 : 1 );
            $movimiento->estado = 2;//recepcionado  
            $movimiento->save();            

            return response()->json(['data'=>$tramite, 'message'=>'Registrado correctamente'], 200); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()."- linea: ".__LINE__." ".__FILE__], 500);
        }   
        
    }
}

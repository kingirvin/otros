@php
$linea = '';
if($item->total > 1){
    if($item->numero == 0)
        $linea = 'izquierda';
    elseif($item->numero + 1 == $item->total)
        $linea = 'derecha';
    else
        $linea = 'centro';
} 
@endphp
<div class="paso {{ $linea }}">    
    <div class="primero">
        <div class="cv_linea"></div>
        <div class="card s_box">  
            <div class="card-header" style="padding: 0.75rem 1rem !important;">
                <div class="w-100">
                    <div class="row align-items-center">
                        <div class="col">                   
                            <small class="d-block text-muted lh-1">Enviado</small>
                            <div class="text-body lh-1">{{ $item->enviado->format('d/m/Y H:i') }}h</div>
                            <h6 class="text-azure lh-1 mb-0 fw-normal">D-{{ $item->documento }}</h6>
                        </div>
                        <div class="col-auto">                   
                            <span class="avatar" title="{{ $item->envia_nombre }}">{{ $item->envia_siglas }}</span> 
                        </div>
                    </div>     
                </div> 
            </div>
            <div class="card-body" style="padding: 0.75rem 1rem; !important">
                <div class="row align-items-center">
                    <div class="col-auto"> 
                        @if($item->estado == 1)
                        <span class="avatar" title="PENDIENTE DE RECIBIR">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                        </span>
                        @elseif($item->estado == 2)
                        <span class="bg-yellow-lt avatar" title="RECIBIDO">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h11a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-11a1 1 0 0 1 -1 -1v-14a1 1 0 0 1 1 -1m3 0v18" /><line x1="13" y1="8" x2="15" y2="8" /><line x1="13" y1="12" x2="15" y2="12" /></svg>
                        </span>
                        @elseif($item->estado == 3)
                        <span class="bg-blue-lt avatar" title="DERIVADO">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M11.5 21h-4.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v5m-5 6h7m-3 -3l3 3l-3 3" /></svg>
                        </span>
                        @elseif($item->estado == 4)
                        <span class="bg-green-lt avatar" title="ATENDIDO">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="4" rx="2" /><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" /><line x1="10" y1="12" x2="14" y2="12" /></svg>
                        </span>
                        @elseif($item->estado == 5)
                        <span class="bg-red-lt avatar" title="OBSERVADO">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
                        </span>
                        @else                        
                        <span class="bg-dark-lt avatar" title="ANULADO">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                        </span>
                        @endif
                    </div>
                    <div class="col">
                        <div class="fw-bold lh-1 text-truncate" title="{{ $item->nombre }}">{{ $item->nombre }} </div> 
                        <small class="d-block text-muted text-truncate" title="{{ $item->detalle }}">{{ $item->detalle }}</small>
                    </div>
                </div> 
                @if($item->accion != null)
                <hr class="mb-0 mt-2">
                <div class="mt-2">
                    <small class="d-block lh-1 fw-bold">{{$item->accion}}</small>
                    @if($item->accion_otros != null)   
                    <h6 class="text-muted mb-0 lh-1 fw-normal" style="white-space: normal;">{{$item->accion_otros}}</h6>
                    @endif
                </div> 
                @endif              
            </div>  
            @if($item->estado > 1)  
            <div class="card-footer" style="padding: 0.75rem 1rem; !important">
                <div class="row align-items-center">
                    <div class="col">                   
                        <small class="d-block text-muted lh-1">Recibido</small>
                        <div class="text-body lh-1">{{ $item->recibido->format('d/m/Y H:i') }}h</div>                        
                    </div>                    
                    <div class="col-auto">                   
                        <span class="avatar" title="{{ $item->recibe_nombre }}">{{ $item->recibe_siglas }}</span> 
                    </div>                    
                </div>
                <!--ATENDIDO-->
                @if($item->atendido != null)
                <hr class="mb-0 mt-1">  
                <div class="row align-items-center mt-1">
                    <div class="col">                   
                        <small class="d-block text-muted lh-1">Atendido</small>
                        <div class="text-body lh-1">{{ $item->atendido->format('d/m/Y H:i') }}h</div>                        
                    </div>                    
                    <div class="col-auto">                   
                        <span class="avatar" title="{{ $item->atendido_nombre }}">{{ $item->atendido_siglas }}</span> 
                    </div>                    
                </div> 
                    @if($item->atendido_observacion != null) 
                    <hr class="mb-0 mt-1"> 
                    <div class="pt-2" style="white-space: normal !important;">                        
                        <h6 class="text-muted mb-0 lh-1 fw-normal">{{ $item->atendido_observacion }}</h6>                        
                    </div>
                    @endif
                @endif
                <!--OBSERVADO-->
                @if($item->observaciones != null)   
                @if(count($item->observaciones) > 0)   
                <hr class="mb-0 mt-1">  
                <div class="row align-items-center mt-1">
                    <div class="col">                   
                        <small class="d-block text-muted lh-1 mt-1">Observado</small>
                        <div class="text-body lh-1">{{ $item->observaciones[0]->created_at->format('d/m/Y H:i') }}h</div>
                        <div style="white-space: normal !important;">                        
                            <h6 class="text-danger mb-0 lh-1 fw-normal">{{ $item->observaciones[0]->detalle }}</h6>                        
                        </div>
                    </div>                    
                    <div class="col-auto">                   
                        <span class="avatar" title="{{ $item->observaciones[0]->user->nombre.' '.$item->observaciones[0]->user->apaterno.' '.$item->observaciones[0]->user->amaterno }}">{{ $item->observaciones[0]->user->siglas }}</span> 
                    </div> 
                </div> 
                @endif
                @endif
            </div> 
            @endif
        </div>
    </div>    
    @if(count($item->despues)>0)
    <div class="siguientes">    
        <div class="cv_linea"></div>
        @each('secciones.movimiento', $item->despues, 'item')
    </div>
    @endif    
</div>
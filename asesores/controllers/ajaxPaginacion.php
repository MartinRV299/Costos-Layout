<?php
class Paginacion{
    public $paginasAdyacentes = 5;//es la cantidad de links que se van a mostrar en la barra de paginación + la primera y/o la última
    public $totalPaginas;
    public $paginacion;//guardamos todo lo que se va a imprimir
    public $registrosPorPagina;
    public $paginaActual = 1;
    public $parametrosOpcionales ='';
    public $target;
    
    function __construct($registros=0){
        $this->registrosPorPagina = $registros == 0  ? 25 : $registros;//si se introduce un valor de 0 en el constructor o si se deja vacio, por defecto mostraremos 25 registros por pagina
    }
    
    public function target($target){
        $this->target = $target;
    }

    public function parametrosPaginadorSolicitudes($nivel,$idRh){ //metodo llamado desde paginador solicitudes
        $this->parametrosOpcionales .= "idusuario="."'".$idRh."'";
        $this->parametrosOpcionales .= " nivel="."'".$nivel."'";//true = RH, false = JEFE
    }

    public function parametrosPaginadorNutricion($target){ //metodo llamado desde paginador nutrición
        $this->parametrosOpcionales .= "apuntar="."'".$target."'";
    }
    
    public function parametroCliente($valor){ 
        $this->parametrosOpcionales .= "cliente="."'".$valor."'";
    }

    public function parametroFacturado($valor){ 
        $this->parametrosOpcionales .= "facturado="."'".$valor."'";
    }

    public function parametroNomina($valor){ 
        $this->parametrosOpcionales .= "nomina="."'".$valor."'";
    }

    public function parametroLiberado($valor){ 
        $this->parametrosOpcionales .= "liberado="."'".$valor."'";
    }

    public function parametroPago($valor){ 
        $this->parametrosOpcionales .= "pago="."'".$valor."'";
    }

    public function parametroNominista($valor){ 
        $this->parametrosOpcionales .= "nominista="."'".$valor."'";
    }

    public function parametrosAutorizacion($valor){
        $this->parametrosOpcionales .= "autorizacion="."'".$valor."'";
    }



    #Esta función es llamada para establecer el LIMIT en la consulta SQL
    public function limitRegistros(){
        $post_por_pagina = $this->registrosPorPagina;
        $inicio = ($this->paginaActual > 1) ? $this->paginaActual * $post_por_pagina - $post_por_pagina : 0;
        return $limit =  " LIMIT ".$inicio.",".$post_por_pagina ;
    }

    #Devuelve la pagina actual de la paginación, por default usamos la variable p
    public function paginaActual($pagina){
        $this->paginaActual = (int)$pagina;
    }
    
    #Establecemos cuantos paginas tendra nuestra paginación
    public function totalPaginas($totalregistros){
        $post_por_pagina = $this->registrosPorPagina;
        $total_post = $totalregistros;//necesito saber el total de registros
        $numero_paginas = ceil($total_post / $post_por_pagina);
        return $this->totalPaginas = $numero_paginas;
    }

    #Realiza todos los calculos de la paginación y los guarda en la variable paginacion
    public function paginacion(){

        $this->paginacion='';
        
        if($this->totalPaginas > 1)
        {
            if ($this->paginaActual === 1)//boton pagina anterior
                $this->paginacion.='<li class="disabled">&laquo;</li>';
            else
                $this->paginacion.='<li actual="'.($this->paginaActual-1).'"><a class="formularioDinamico paginadorDinamico2 '.$this->target.'"  href="">&laquo;</a></li>';
               
            if ($this->totalPaginas < 6){ //paginaciones de máximo 5 paginas
                for($i = 1; $i <= $this->totalPaginas; $i++){
                    if ($this->paginaActual === $i)
                        $this->paginacion.='<li class="active">'. $i .'</li>';
                    else
                        //$this->paginacion.='<li><a class="formularioDinamico" href="'.Ruta::ruta_server().$this->target.'/'.$i.'">'. $i.'</a></li>';
                        $this->paginacion.='<li actual="'.$i.'"><a class="formularioDinamico paginadorDinamico2 '.$this->target.'" href="">'. $i.'</a></li>';
                }
            }


            else{ //paginaciones de 6 o más paginas

                if ($this->paginaActual < $this->paginasAdyacentes){
                    for ($i = 1; $i <= $this->paginasAdyacentes; $i++){
                        if ($i === $this->paginaActual )
                            $this->paginacion.='<li class="active">'.$i.'</li>';
                        else
                           $this->paginacion.='<li actual="'.$i.'"><a class="formularioDinamico paginadorDinamico2 '.$this->target.'" href="">'. $i.'</a></li>';   
                    }
                    $this->paginacion.=" <i class='fa fa-link fa-lg' aria-hidden='true' style='transform: rotate(135deg); color:#037e8c;'></i> ";
                    $this->paginacion.='<li actual="'.$this->totalPaginas.'"><a class="formularioDinamico paginadorDinamico2 '.$this->target.'" href="">'. $this->totalPaginas .'</a></li>';			
                }


                else if ( ( $this->totalPaginas + 2 ) - $this->paginasAdyacentes > $this->paginaActual ){
                    $this->paginacion.='<li actual="1"><a class="formularioDinamico paginadorDinamico2 '.$this->target.'" href="">1</a></li>';//$this->paginacion.='<li><a href="'.$this->target.'?p=1'.$this->campoBusqueda.'">1</a></li>';
                    $this->paginacion.=" <i class='fa fa-link fa-lg' aria-hidden='true' style='transform: rotate(135deg); color:#037e8c;'></i> ";
                    for ($i = $this->paginaActual - ((int)($this->paginasAdyacentes/2));$i <= $this->paginaActual + ((int)($this->paginasAdyacentes/2)); $i++){
                        if ($i == $this->paginaActual)
                            $this->paginacion.='<li class="active">'.$i.'</li>';
                        else
                        $this->paginacion.='<li actual="'.$i.'"><a class="formularioDinamico paginadorDinamico2 '.$this->target.'" href="">'. $i.'</a></li>';//$this->paginacion.='<li><a href="'.$this->target.'?p='.$i.$this->campoBusqueda.'">'. $i.'</a></li>';
                    }
                    $this->paginacion.=" <i class='fa fa-link fa-lg' aria-hidden='true' style='transform: rotate(135deg); color:#037e8c;'></i> ";
                    $this->paginacion.='<li actual="'.$this->totalPaginas.'"><a class="formularioDinamico paginadorDinamico2 '.$this->target.'" href="">'. $this->totalPaginas .'</a></li>';// $this->paginacion.='<li><a href="'.$this->target.'?p='.$this->totalPaginas.$this->campoBusqueda.'">'.$this->totalPaginas.'</a></li>';
                }


                else{
                    $this->paginacion.='<li actual="1"><a class="formularioDinamico paginadorDinamico2 '.$this->target.'" href="">1</a></li>';	
                    $this->paginacion.=" <i class='fa fa-link fa-lg' aria-hidden='true' style='transform: rotate(135deg); color:#037e8c;'></i> ";
                    for ($i = ( $this->totalPaginas + 1) - $this->paginasAdyacentes; $i <= $this->totalPaginas; $i++){
                        if ($i == $this->paginaActual)
                            $this->paginacion.='<li class="active">'.$i.'</li>';
                        else
                        $this->paginacion.='<li actual="'.$i.'"><a class="formularioDinamico paginadorDinamico2 '.$this->target.'" href="">'. $i.'</a></li>';//$this->paginacion.='<li><a href="'.$this->target.'?p='.$i.$this->campoBusqueda.'">'.$i.'</a></li>';
                    }
                }
            
            }


            if($this->paginaActual == $this->totalPaginas) //boton pagina siguiente
                $this->paginacion.='<li class="disabled">&raquo;</li>';
            else
               $this->paginacion.='<li actual="'.($this->paginaActual+1).'"><a class="formularioDinamico paginadorDinamico2 '.$this->target.'" href="">&raquo;</a></li>'; 
        }
        return true;
    }

    #permite imprimir la paginación
    public function mostrar(){
        if($this->paginacion())
            return '<section class="paginacion">
                        <ul registros="'.$this->registrosPorPagina.'" target="'.$this->target.'" '.$this->parametrosOpcionales.'>
                            '.$this->paginacion.'
                        </ul>
                    </section>';
    }
}


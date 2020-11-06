class Facturacion{

    static init(){
        Facturacion.contenedorPrincipal = $('#controlFacturacion');
        Facturacion.dataPrincipal = $('#dataFacturacion');
        Facturacion.mostrarPaginador = $('.paginadorFacturacion');
        Facturacion.modalFacturacion = $('#modalFacturacion');
        Facturacion.consecutivoNominaLabel = $('#consecutivoNominaLabel');
        Facturacion.dataModal = $('#dataModal');
        Facturacion.filtroFolio = $('#filtroFolio');
        Facturacion.filtroCliente = $('#filtroCliente');
        Facturacion.filtroMonto = $('#filtroMonto');
        Facturacion.totalRegistros = $('#totalRegistrosFacturacion');
        $('.monetario').mask('000,000,000.00', {reverse: true});
        Facturacion.limpiarFiltros = $('#actualizarFacturacion');
    }

    static ajax(){
        Facturacion.botonGuardar = $('#botonGuardar');
        Facturacion.botonActualizar = $('#botonActualizar');
        Facturacion.campoActualizar = $(".actualizar");
        Facturacion.formularioActualizar = $("#formularioFacturacion");

        Facturacion.estatusFactura = $('#estatusFactura');
        Facturacion.numeroFactura = $('#numeroFactura');
        Facturacion.numeroFacturaCss = $(".numeroFacturaCss");

        Facturacion.numeroNota = $("#numeroNota");
        Facturacion.numeroNotaCss = $(".numeroNotaCss");
        Facturacion.fechaFacturacion = $("#fechaFacturacion");
        Facturacion.fechaFacturacionCss = $(".fechaFacturacionCss");
        Facturacion.fechaPagoFacturacion = $("#fechaPagoFacturacion");
        Facturacion.fechaPagoFacturacionCss = $(".fechaPagoFacturacionCss");

        Facturacion.subtotal = $("#facturaSubtotal");
        Facturacion.retencionIva = $("#facturaRetencionIva");
        Facturacion.iva = $("#facturaIva");
        Facturacion.total = $("#facturaTotal");
        Facturacion.retencionIsn = $("#facturaRetencionIsn");

        $('.monetario').mask('000,000,000.00', {reverse: true})
    }

    static paginar(elemento){
        let datos = new FormData();
        datos.append("paginaActual", $(elemento).parent().attr("actual"));
        datos.append("registrosPorPagina", $(elemento).parent().parent().attr("registros"));
        datos.append("target", $(elemento).parent().parent().attr("target"));
        datos.append("cliente", $(elemento).parent().parent().attr("cliente"));
        datos.append("nomina", $(elemento).parent().parent().attr("nomina"));
        datos.append("facturado", $(elemento).parent().parent().attr("facturado"));
        datos.append("liberado", $(elemento).parent().parent().attr("liberado"));
        datos.append("pago", $(elemento).parent().parent().attr("pago"));
        datos.append("url", window.location.pathname);
        datos.append("nominista",$(elemento).parent().parent().attr("nominista"));
        datos.append("autorizacion",$(elemento).parent().parent().attr("autorizacion"));
        Facturacion.recargarPaginador(datos);
    }

    static filtros(){
        let datos = new FormData();
        datos.append("paginaActual", 1);
        datos.append("registrosPorPagina", Facturacion.mostrarPaginador.find('ul').attr('registros'));
        datos.append("target", Facturacion.mostrarPaginador.find('ul').attr('target'));
        datos.append("cliente", Facturacion.filtroCliente.val());
        datos.append("nomina", Facturacion.filtroFolio.val());
        datos.append("facturado", MetodosDiversos.convertirDecimal(Facturacion.filtroMonto.val()));
        datos.append("liberado", '');
        datos.append("pago", '');
        datos.append("url",window.location.pathname);
        datos.append("nominista",'');
        datos.append("autorizacion",'1');
        Facturacion.recargarPaginador(datos);
    }

    static recargarPaginador(datos){
        Facturacion.dataPrincipal.html('<div style="text-align:center"><i class="fa fa-circle-o-notch fa-pulse fa-fw" style="font-size:110px;margin-top:5%;margin-bottom:5%;color:#3489df;"></i></div>');
        MetodosDiversos.consultaAjaxFormulario("controllers/AjaxNominas.php", datos,(error,respuesta)=>{
            if(error)console.log('error',error);
            else {
                Facturacion.dataPrincipal.html(respuesta.html);
                Facturacion.mostrarPaginador.html(respuesta.paginador);
                Facturacion.totalRegistros.html(respuesta.total);
            }
        });
    }

    static modal(folio){
        Facturacion.modalFacturacion.modal('show');
        Facturacion.consecutivoNominaLabel.text(folio);
        let data={folioNomina:folio,url : window.location.pathname}
        MetodosDiversos.consultaAjaxData("controllers/AjaxNominas.php", data,(error,respuesta)=>{
            if(error)
                console.log('Ocurrio un error: ',respuesta); 
            else{
                    Facturacion.dataModal.html(respuesta.html);
                    /*****************************************/
                    Facturacion.ajax();
                    Facturacion.campoActualizar.prop('disabled',true);
                    Facturacion.botonGuardar.hide();
                   
    
                    Facturacion.botonActualizar.click(function(){
                        $(this).hide();
                        Facturacion.botonGuardar.show();
                        Facturacion.campoActualizar.prop('disabled',false);
                    });
    
                    Facturacion.formularioActualizar.submit(function(e){
                        e.preventDefault();
                        let data = new FormData($(this)[0]); 
                        data.append("actualizarFacturacion", folio);
                        data.append("nominasTotalCalculado", Facturacion.total.val());
    
                        Facturacion.actualizarFormulario(data,function(respuesta){
                            if(!respuesta){
                                Facturacion.botonActualizar.show();
                                Facturacion.botonGuardar.hide();
                                Facturacion.campoActualizar.prop('disabled',true);
                            }
                        });
                    });

                    Facturacion.estatusFactura.change(function(){
                        Facturacion.fechaPagoFacturacion.attr('required',false);
                        Facturacion.fechaPagoFacturacionCss.css('display','none');
                        Facturacion.numeroNota.attr('required',false);
                        Facturacion.numeroNotaCss.css('display','none');
                        Facturacion.numeroFactura.attr('required',false);
                        Facturacion.numeroFacturaCss.css('display','none');
                        if($(this).val() == 2){
                            Facturacion.fechaPagoFacturacion.attr('required',true);
                            Facturacion.fechaPagoFacturacionCss.css('display','');
                        }
                        else if($(this).val() == 3){
                            Facturacion.numeroNota.attr('required',true);
                            Facturacion.numeroNotaCss.css('display','');
                            Facturacion.numeroFactura.attr('required',true);
                            Facturacion.numeroFacturaCss.css('display','');
                        }
                    });

                    Facturacion.numeroFactura.on('input',function(){
                        if($(this).val() != ""){
                            Facturacion.fechaFacturacion.attr('required',true);
                            Facturacion.fechaFacturacionCss.css('display','');
                        }
                        else{
                            Facturacion.fechaFacturacion.attr('required',false);
                            Facturacion.fechaFacturacionCss.css('display','none');
                        }
                    });

                    Facturacion.retencionIsn.on('input',function(){
                        Facturacion.calcularRetencionIsn();
                    });
      
                }
            });
    }


    static calcularRetencionIsn(){
        let total =
        parseFloat(MetodosDiversos.convertirDecimal(Facturacion.subtotal.val())) +
        parseFloat(MetodosDiversos.convertirDecimal(Facturacion.iva.val())) -
        parseFloat(MetodosDiversos.convertirDecimal(Facturacion.retencionIva.val())) -
        parseFloat(MetodosDiversos.convertirDecimal(Facturacion.retencionIsn.val()));
        if(total > 0)
            Facturacion.total.val( MetodosDiversos.mascaraMoneda(total,1) );
        else
            Facturacion.total.val('');
    }

    static actualizarFormulario(data,callback){
        MetodosDiversos.consultaAjaxFormulario("controllers/AjaxNominas.php", data,(error,respuesta)=>{
                if(error)
                    MetodosDiversos.mostrarRespuesta('error',respuesta.titulo,respuesta.subtitulo,30000,true); 
                else 
                    MetodosDiversos.mostrarRespuesta('success',respuesta.titulo,respuesta.subtitulo,30000,true);
                callback(error);
        });
    }

    static main(){
        Facturacion.init();
        Facturacion.filtroFolio.on('input',()=>Facturacion.filtros());
        Facturacion.filtroCliente.change(()=>Facturacion.filtros());
        Facturacion.filtroMonto.on('input',()=>Facturacion.filtros());
        Facturacion.contenedorPrincipal.on('click','.nominasMostrarData',function(){Facturacion.modal( $(this).attr('value'));});
        Facturacion.contenedorPrincipal.on('click','.facturacion',function(e){e.preventDefault();Facturacion.paginar($(this));
        });
        Facturacion.limpiarFiltros.click(function(){
            Facturacion.filtroFolio.val('');
            Facturacion.filtroCliente.val('');
            Facturacion.filtroMonto.val('');
            Facturacion.filtros();
        });
    }
}

Facturacion.main();
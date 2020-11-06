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
        Facturacion.modalArchivosAdjuntos = $('#modalArchivosAdjuntos');
        Facturacion.dataArchivosAdjuntos = $('#dataArchivosAdjuntos');
        Facturacion.labelArchivosAdjuntos = $('#labelArchivosAdjuntos');
        Facturacion.archivosMasivos = $('#adjuntarArchivosMasivosFacturacion');
        Facturacion.formularioCargarLayout = $('#formularioCargarLayout');
        Facturacion.botonCargarLayout = $('#cargarRegistrosFacturacion');
       

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
        $('.monetario').mask('000,000,000.00', {reverse: true});

        Facturacion.lienzo = $('#lienzoAdjuntos');
        Facturacion.documentos = $('#documentos');
        Facturacion.lienzoDisabled = $('#lienzoAdjuntosDisabled');
        Facturacion.responsable = $('#actualizarFacturacionComentarios');
        Facturacion.labelTotalArchivos = $('#totalAdjuntos');
        Facturacion.labelPesoArchivos = $('#totalPeso');
        Facturacion.botonAdjuntaDocumentos = $('#adjuntarDocumentos');
        Facturacion.archivos = Array();
        Facturacion.pesoArchivos = 0;
        Facturacion.totalArchivos = 0;
        Facturacion.recargarArchivos = $('#recargarArchivos');
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
                        Facturacion.lienzo.show();
                        Facturacion.lienzoDisabled.hide();
                    });
    
                    Facturacion.formularioActualizar.submit(function(e){
                        e.preventDefault();
                        let data = new FormData($(this)[0]); 
                        data.append("actualizarFacturacion", folio);
                        data.append("nominasTotalCalculado", Facturacion.total.val());

                        let total = Facturacion.archivos.length;
                        if(total > 0){
                            for(let i=0;i<total;i++)
                                data.append("files"+i, Facturacion.archivos[i]);
                            data.append("totalFile",total); 
                        }
                        data.append("url",window.location.pathname);
    
                        
                        Facturacion.actualizarFormulario(data,function(respuesta){
                            if(!respuesta){
                                Facturacion.botonActualizar.show();
                                Facturacion.botonGuardar.hide();
                                Facturacion.campoActualizar.prop('disabled',true);
                                Facturacion.lienzo.hide();
                                Facturacion.lienzoDisabled.show();
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
                    Facturacion.documentos.on('click','.attachTickets',function(){
                        Facturacion.botonAdjuntaDocumentos.click();
                    });
                    Facturacion.documentos.on("dragover", function(e){
                        e.preventDefault();
                        e.stopPropagation();
                        if($(this).html() === '<h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default attachTickets"><i class="fa fa-paperclip"></i> Presiona</button></h2>'){
                            $(this).html('');
                            $(this).css({"padding-left":"30px","padding-right":"30px"});
                        }
                        $(this).css({"background":"#007BFF","opacity":".6"});
                    });
                    Facturacion.documentos.on("drop", function(e){
                        e.preventDefault();
                        e.stopPropagation();
                        let files = e.originalEvent.dataTransfer.files; 
                        Facturacion.cargarDocumentos(files);
                    });
                    Facturacion.documentos.on("dragleave", function(e){
                        Facturacion.resetDocumentos();
                    });
                    Facturacion.botonAdjuntaDocumentos.change(function(e){
                        let files = e.target.files;
                        if(Facturacion.documentos.html() === '<h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default attachTickets"><i class="fa fa-paperclip"></i> Presiona</button></h2>'){
                            Facturacion.documentos.html('');
                            Facturacion.documentos.css({"padding-left":"30px","padding-right":"30px"});
                        }
                        Facturacion.cargarDocumentos(files);
                        Facturacion.botonAdjuntaDocumentos.val('');
                    });
                    Facturacion.documentos.on('click','.cancelDocument',function(){
                        let eliminar = $(this).parent().children('span').text();
            
                        let total = eliminar.length;
                        let temp;
            
                        eliminar = eliminar.substring( 0, total-17 );
                        temp = Facturacion.archivos.filter(function(file){
                            return file.name === eliminar;
                        })[0];
            
                        if(temp === undefined){
                            eliminar = eliminar.substring( 0, total-18 );
                            temp = Facturacion.archivos.filter(function(file){
                            return file.name === eliminar;
                            })[0];
                        }
            
                        Facturacion.archivos = Facturacion.archivos.filter(function(file){
                            return file.name !== eliminar;
                        });
            
                        $(this).parent().remove();
                        if(Facturacion.totalArchivos > 1){
                            Facturacion.totalArchivos--;
                            Facturacion.labelTotalArchivos.text(Facturacion.totalArchivos);
                            Facturacion.labelPesoArchivos.text( Facturacion.convertirAmegas(Facturacion.pesoArchivos -= temp.size));
                        }
                        else{
                            Facturacion.resetDocumentos();
                            Facturacion.labelTotalArchivos.text(0);
                            Facturacion.labelPesoArchivos.text('0 MB');
                        }                        
                    });
                }
            });
    }

    static cargarDocumentos(files){
        Facturacion.documentos.css({"opacity":"1"});
        for(let i=0;i<files.length;i++ ){
            let file = files[i];
            let flag = false;

            Facturacion.archivos.filter(function(filesave) {
                if( filesave.name == file.name){
                    flag = true
                    return filesave;
                }
            })[0];
    
            if(!flag){
                let valido = (/\.(?=pdf|PDF|xml|XML)/gi).test(file.name);
                
                if (!valido) {
                    MetodosDiversos.mostrarRespuesta('error','Formato de archivo no valido','Sólo puedes cargar archivos pdf y xml',30000,true);
                    Facturacion.reloadContadores();
                    return;
                }
                    
                if (file.size > 25 * 1024 * 1024){
                    MetodosDiversos.mostrarRespuesta('error','Excediste el máximo permitido de MB por archivo','El tamaño máximo permito por cada archivo pdf es de 25 MB)',30000,true);
                    Facturacion.reloadContadores();
                    return
                }

                if(Facturacion.pesoArchivos + file.size  > (50 * 1024 * 1024)){
                    MetodosDiversos.mostrarRespuesta('error','Excediste el máximo total permitido de MB','El máximo permitido es de 50 MB por carga, para continuar con el proceso deberas eliminar archivos, recuerda que puedes anexar los que te falten posteriormente',30000,true);
                    Facturacion.reloadContadores();
                    return
                }
                    
                Facturacion.documentos.append('<li><span>'+file.name+'</span><span class="close2 cancelDocument" aria-hidden="true" style="margin-right:2px;"><i class="fa fa-times fa-lg" style="color:#fff;" aria-hidden="true"></i></span><span style="color:#fff;margin-right:40px;float:right;font-weight: 700;">'+' Tamaño: ' + Facturacion.convertirAmegas(file.size) +' </span></li>');
                Facturacion.pesoArchivos += file.size;
                ++Facturacion.totalArchivos;
                Facturacion.archivos.push(file);  
            }  
        }
        Facturacion.reloadContadores();
       
    }

    static reloadContadores(){
        Facturacion.labelTotalArchivos.text(Facturacion.totalArchivos);
        Facturacion.labelPesoArchivos.text( Facturacion.convertirAmegas(Facturacion.pesoArchivos));
        Facturacion.resetDocumentos();
    }

    static convertirAmegas(peso){
        return (peso / 1024 / 1024).toFixed(2) + ' MB';
    }
    
    static resetDocumentos(){ 
        if(Facturacion.documentos.html() === ""){
            Facturacion.documentos.html('<h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default attachTickets"><i class="fa fa-paperclip"></i> Presiona</button></h2>');
            Facturacion.documentos.css({"padding-left":"0","padding-right":"0"});
            Facturacion.documentos.css({"opacity":"1"});
        }
        else
            Facturacion.documentos.css({"opacity":"1"});
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
        MetodosDiversos.mostrarRespuesta('','<input type="text" id="progressBar" value="0" data-width="120" data-height="120" data-fgColor="#811363"><br><div class="knob-label" id="loaded_n_total"></div>',' Por favor espere... ',120000,true); 
        $("#progressBar").knob({
            change : function (value) {
            }
        });

        let ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", Facturacion.progressHandler, false);
        ajax.addEventListener("load", function(e){
            let respuesta = JSON.parse(e.srcElement.response);
            if(respuesta.error)
                MetodosDiversos.mostrarRespuesta('error',respuesta.titulo,respuesta.subtitulo,30000,true); 
            else{
                MetodosDiversos.mostrarRespuesta('success',respuesta.titulo,respuesta.subtitulo,30000,true);
                Facturacion.responsable.html(respuesta.html);
                Facturacion.archivos = Array();
                Facturacion.pesoArchivos = 0;
                Facturacion.totalArchivos = 0;
                Facturacion.labelTotalArchivos.text(0);
                Facturacion.labelPesoArchivos.text('0 MB');
                Facturacion.documentos.html('<h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default attachTickets"><i class="fa fa-paperclip"></i> Presiona</button></h2>');
                Facturacion.documentos.css({"padding-left":"0","padding-right":"0"});
                Facturacion.documentos.css({"opacity":"1"});
                if(respuesta.archivos !== undefined)
                    Facturacion.recargarArchivos.html(respuesta.archivos.archivos);
            }
            callback(respuesta.error);
        }, false);
        ajax.addEventListener("error", Facturacion.errorHandler, false);
        ajax.addEventListener("abort", Facturacion.abortHandler, false);
        ajax.open("POST", "controllers/AjaxNominas.php");
        ajax.send(data);

        /*MetodosDiversos.consultaAjaxFormulario("controllers/AjaxNominas.php", data,(error,respuesta)=>{
                if(error)
                    MetodosDiversos.mostrarRespuesta('error',respuesta.titulo,respuesta.subtitulo,30000,true); 
                else{
                    MetodosDiversos.mostrarRespuesta('success',respuesta.titulo,respuesta.subtitulo,30000,true);
                    Facturacion.responsable.html(respuesta.html);
                    Facturacion.archivos = Array();
                    Facturacion.pesoArchivos = 0;
                    Facturacion.totalArchivos = 0;
                    Facturacion.labelTotalArchivos.text(0);
                    Facturacion.labelPesoArchivos.text('0 MB');
                    Facturacion.documentos.html('<h2>Arrastra y suelta los archivos que desees adjuntar o <button type="button" class="btn btn-default attachTickets"><i class="fa fa-paperclip"></i> Presiona</button></h2>');
                    Facturacion.documentos.css({"padding-left":"0","padding-right":"0"});
                    Facturacion.documentos.css({"opacity":"1"});
                }
                callback(error);
        });*/

    }

    static progressHandler(e){
        $('#loaded_n_total').text( MetodosDiversos.formatBytes(e.loaded) + " de " + MetodosDiversos.formatBytes(e.total));
       let percent = (e.loaded / e.total) * 100;
       let redondeo = Math.round(percent);
       Facturacion.knobfunction(redondeo);
    }

    static knobfunction(value1){
        $('#progressBar').val(value1).trigger('change');
    }

    static errorHandler(){
        console.log('Algo fallo'); 
    }

    static abortHandler(){
        console.log('Se aborto')
    }

    static botonArchivosAdjuntos(id,location){
        Facturacion.modalArchivosAdjuntos.modal('show');
        MetodosDiversos.consultaAjaxData("controllers/AjaxNominas.php",{archivosAdjuntos:id,location},(error,respuesta)=>{ 
            if(error)
                MetodosDiversos.mostrarRespuesta('error',respuesta.titulo,respuesta.subtitulo,30000,true);
            else{
                Facturacion.dataArchivosAdjuntos.html(respuesta.html);
                Facturacion.labelArchivosAdjuntos.text(respuesta.total);
            }
        });
    }

    static cargarMasivos(data){

        MetodosDiversos.mostrarRespuesta('','<input type="text" id="progressBar" value="0" data-width="120" data-height="120" data-fgColor="#811363"><br><div class="knob-label" id="loaded_n_total"></div>',' Por favor espere... ',120000,true); 
        
        $("#progressBar").knob({
            change : function (value) {
            }
        });

        let ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", Facturacion.progressHandler, false);
        ajax.addEventListener("load", function(e){
            let respuesta = e.loaded;
            if(parseInt(respuesta) === 0)
                MetodosDiversos.mostrarRespuesta('error','Excediste el máximo total permitido de MB','El máximo permitido es de 50 MB por carga, para continuar con el proceso deberas eliminar archivos, recuerda que puedes anexar los que te falten posteriormente',30000,true);
            else{
                let respuesta = JSON.parse(e.srcElement.response);
                MetodosDiversos.mostrarRespuesta('success',respuesta.titulo,respuesta.subtitulo,30000,true);
            }
                
        }, false);
        ajax.addEventListener("error", Facturacion.errorHandler, false);
        ajax.addEventListener("abort", Facturacion.abortHandler, false);
        ajax.open("POST", "controllers/AjaxNominas.php");
        ajax.send(data);
    }

    static cargaManual(data){
        
       MetodosDiversos.consultaAjaxFormulario("controllers/AjaxNominas.php", data,(error,respuesta)=>{
            console.log(respuesta);
           if(error){
               MetodosDiversos.mostrarRespuesta('error',respuesta.titulo,respuesta.subtitulo,30000,true);
           }  
           else if(respuesta.alerta){
               MetodosDiversos.mostrarRespuesta('warning',respuesta.titulo,respuesta.subtitulo,30000,true);
               //Nominas.actualizarNominas();
           }
           else{
                MetodosDiversos.mostrarRespuesta('success',respuesta.titulo,respuesta.subtitulo,30000,true);
                //Nominas.actualizarNominas();
           }
           Facturacion.botonCargarLayout.val('');
           if(respuesta.log){ //si ocurrieron errores o advertencias se genera el archivo de logs
               Facturacion.log(respuesta.dataLog);
            }
        }); 
    }

   static log(data){

        let texto = data;
      
        let textFileAsBlob = new Blob([texto], {
            type: 'text/plain;charset=utf-8'
        });

        let downloadLink = document.createElement("a");
        downloadLink.download =  "resultados.txt";
        window.URL = window.URL || window.webkitURL;
        downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
        downloadLink.onclick = Facturacion.destroyClickedElement;
        document.body.appendChild(downloadLink);
        downloadLink.click();
    }

    static destroyClickedElement(event) {
        document.body.removeChild(event.target);
    }
    

    static main(){
        Facturacion.init();
        Facturacion.filtroFolio.on('input',()=>Facturacion.filtros());
        Facturacion.filtroCliente.change(()=>Facturacion.filtros());
        Facturacion.filtroMonto.on('input',()=>Facturacion.filtros());
        Facturacion.contenedorPrincipal.on('click','.nominasMostrarData',function(){Facturacion.modal( $(this).attr('value'));});
        Facturacion.contenedorPrincipal.on('click','.facturacion',function(e){e.preventDefault();Facturacion.paginar($(this));});
        Facturacion.limpiarFiltros.click(()=>{
            Facturacion.filtroFolio.val('');
            Facturacion.filtroCliente.val('');
            Facturacion.filtroMonto.val('');
            Facturacion.filtros();
        });

        $('body').on("dragover", function(e){
            e.preventDefault();
            e.stopPropagation();
        });
        $('body').on("drop", function(e){
            e.preventDefault();
            e.stopPropagation();
        });
        Facturacion.contenedorPrincipal.on('click','.visor-pdf-crow-nominas',function(){
            mostrarVisorCrowPdf($(this));
        });
        Facturacion.contenedorPrincipal.on('click','.eliminarAdjuntoNominas',function(){
            MetodosDiversos.crearRegistro('<i class="fa fa-trash text-red fa-3x fa-fw"></i>',`¿ Estas seguro que deseas ELIMINAR el archivo ?`,callback=>{
                if(callback){
                    let id = $(this).attr('name').split('-');
                    MetodosDiversos.consultaAjaxData("controllers/AjaxNominas.php",{eliminarArchivo:$(this).attr('name'),rutaCarpeta:id[0]},(error,respuesta)=>{
                        if(error){
                            MetodosDiversos.mostrarRespuesta('error',respuesta.titulo,respuesta.subtitulo,30000,true);
                            return;
                        }
                        $(this).parent().parent().parent().remove();
                    });
                }
            },false);
        });

        Facturacion.dataModal.on('click','.botonArchivosAdjuntos',function(){
            Facturacion.botonArchivosAdjuntos($(this).attr('nomina-data'),$(this).attr('location'));
        });

        Facturacion.archivosMasivos.change(function(e){
            let datosFormulario = new FormData(); 
            let archivos = e.target.files;
            let total = archivos.length;
            let peso = 0;
            if(total > 0){
                for(let i=0;i<total;i++ ){
                    datosFormulario.append("files"+i, archivos[i]);
                    peso += archivos[i].size;
                    datosFormulario.append("ruta"+i,archivos[i].webkitRelativePath);
                }
                datosFormulario.append("totalArchivosMasivos",total); 
                datosFormulario.append("ruta",window.location.pathname+2);
                Facturacion.archivosMasivos.val('');

                if(peso > (50 * 1024 * 1024)){
                    MetodosDiversos.mostrarRespuesta('error','Excediste el máximo total permitido de MB','El máximo permitido es de 50 MB por carga, para continuar con el proceso deberas eliminar archivos, recuerda que puedes anexar los que te falten posteriormente',30000,true);
                    return;
                }
                Facturacion.cargarMasivos(datosFormulario);
            }
        });

        Facturacion.botonCargarLayout.change(function(e){
          swal({
                title: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>',
                text: ' Por favor espere...',
                type: '',
                showConfirmButton: false,
                allowOutsideClick: false
            });

              let file = e.target.files[0];
            let valido = (/\.(?=xlsx)/gi).test(file.name);
            
            if (!valido) {
                Facturacion.cargarLayout.val('');
                swal("Formato no válido", "Formatos válido: .xlsx", "error").catch(swal.noop);
                return;
            }

          
            let formulario = new FormData (Facturacion.formularioCargarLayout[0]);
            formulario.append("cargaManual",window.location.pathname);
            Facturacion.cargaManual(formulario);
        });

    }
}

Facturacion.main();
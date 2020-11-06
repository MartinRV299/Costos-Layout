class MetodosDiversos{
    
    static validate_date(fecha){
        let patron=new RegExp("^(19|20)+([0-9]{2})([-])([0-9]{1,2})([-])([0-9]{1,2})$");
        if(fecha.search(patron)==0)
            return true;
        return false;
    }

    static init_validate_date(valor){
        let fecha=valor;
        let anio=fecha.split("-");
        if( anio[0] > 2018 && anio[1] > 0){
            return MetodosDiversos.validate_date(fecha)
        }	
    }

    static mostrarRespuesta(tipo,titulo,subtitulo,tiempo,confirmacion=false){
        swal({
            title: titulo,
            text: subtitulo,
            type: tipo,
            timer: tiempo,
            showConfirmButton: confirmacion,
            allowOutsideClick: false
        }).catch(swal.noop);
    }

    static consultaAjaxFormulario(ruta,data,callback){
        $.ajax({
            url: ruta_server + ruta,
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json"
        }).done(function(respuesta) {
            callback(respuesta.error,respuesta);
        }).fail(function(error) {
            callback(true,error);
        });  
    }

    static consultaAjaxData(ruta,data,callback){
        $.ajax({
            url: ruta_server + ruta,
            type: "POST",
            data: data,
            dataType: "json"
        }).done(function(respuesta) {
            callback(respuesta.error,respuesta);
        }).fail(function(error) {
            callback(true,error);
        });  
    }

    static crearRegistro(titulo,subtitulo,callback,icono = true){

        let i = icono === true ? 'question' : '';
        swal({
            title: titulo,
            text: subtitulo,
            type: i,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡Sí, continuar!',
            cancelButtonText: '¡No!'
        }).then((result) => {
            callback(true);            
        }).catch((result)=> {
            callback(false);
        });
    }

    static obtenerAnio(fecha){
        $('#asignarAnio').text(fecha);
        let usuario = $('#calendario').attr('name');
        
        MetodosDiversos.consultaAjaxData("controllers/ajaxPermisos.php",{cargarPermisos:true,anio:fecha,usuario},(error,respuesta)=>{
            if(!error){
                $('#asignarVaccaionesDisfrutadas').text(respuesta.disfrutadas);
                $('#asiganarBonosPlus').text(respuesta.bonos);
                $('#asignarPermisos').text(respuesta.permisos);
                $('#asignarFaltas').text(respuesta.faltas);
                $('#asignarPorAutorizar').text(respuesta.porAutorizar);
            } 
        });
    }

}

/*Ejemplo para validar fechas

$('#user_date').change(function(){
	MetodosDiversos.init_validate_date($(this).val());
});*/
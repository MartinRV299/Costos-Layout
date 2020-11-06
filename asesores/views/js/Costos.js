class Costos{

    static  init() 
    {   
       
        Costos.formlayout = $('#formlayout');
        Costos.formdescarga = $('#formulariodescarga');
        Costos.button = $('#descarga');
        Costos.archivo = $('#file');
        
    }
    static cargaManual(data){
        MetodosDiversos.consultaAjaxFormulario("controllers/AjaxCostos.php", data,(error,respuesta)=>{
            if (respuesta.log == true) {
                if (respuesta.alerta == false){
                    MetodosDiversos.mostrarRespuesta('error',respuesta.dataLog,'Consulte el archivo de errores descargado',30000,true);
                }if(respuesta.alerta == true){
                MetodosDiversos.mostrarRespuesta('error',respuesta.dataLog,'Consulte el archivo de errores descargado',30000,true);
                Costos.log(respuesta.error);
                }
            }else if (respuesta.version == false) {

                MetodosDiversos.mostrarRespuesta('error',respuesta.dataLog,'Actualice la pagina y descargue el nuevo layout de COSTOS',30000,true);
            }else if(respuesta.alerta > 0){
                if (respuesta.fila == 0) {
                    MetodosDiversos.mostrarRespuesta('error','ERROR EN EL UNICO REGISTRO EXISTENTE','Consulte el documento texto de errores',30000,true);
                    Costos.log(respuesta.error)
                    
                }else{
                    MetodosDiversos.mostrarRespuesta('error','EL ARCHIVO CARGADO CONTIENE ERRORES','Consulte el documento texto de errores',30000,true);
                    Costos.log(respuesta.error)} 
            }else {
                MetodosDiversos.mostrarRespuesta('success',respuesta.dataLog,'Archivo cargado exitosamenteee',30000,true);
                }
            Costos.archivo.val('');

         });
    }
    
    static log(data)
    {
        let texto = data;
        let textFileAsBlob = new Blob([texto],{
            type: 'text/plain;charset=utf-8'
        })
        let downloadLink = document.createElement("a");
        downloadLink.download =  "errores-resultados.txt";
        window.URL = window.URL || window.webkitURL;
        downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
        downloadLink.onclick = Costos.destroyClickedElement;
        document.body.appendChild(downloadLink);
        downloadLink.click();
        
    }

    static main()
    {
    
    Costos.init();
    $(document).ready(function(){
        Costos.archivo.change('click', function(e){
            
                let file = e.target.files[0];
                let valido =(/\.(?=xlsx)/gi).test(file.name);
                if(!valido){
                    alert("ERROR SOLO ADMITE ARCHIVO .XLSX");
                }
                let formulario = new FormData(Costos.formlayout[0]);
                Costos.cargaManual(formulario);
                
            });

        }); 
        
    }

}
Costos.main();


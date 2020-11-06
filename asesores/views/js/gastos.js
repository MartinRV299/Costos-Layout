

class Gastos{

    static  init() 
    {   
       
        Gastos.contenedorClientes = $('#controlCompras');
        Gastos.areaAgregarContacto = $('#areaContacto');
        Gastos.areaAgregarFacturadora = $('#areaFacturadora');
        Gastos.areaAgregarImss = $('#areaImss');
        Gastos.areaAgregarAsimilados = $('#areaAsimilados');
        Gastos.botonCancelarFormulario = $('#cancelarClientes');
        Gastos.formulario = $('#formularioCompras');
        Gastos.enviar = $('#enviar');
        //Campos Formulario Gastos
        Gastos.Nombre = $('#Nombre');
        Gastos.Departamento = $('#Departamento');
        Gastos.Fecha = $('#Fecha');
        Gastos.Rfc = $('#Rfc');
        Gastos.Proveedor = $('#Proveedor');
        Gastos.Telefono = $('#Telefono');
        Gastos.Email = $('#Email');
        Gastos.Direccion = $('#Direccion');
       // Gastos.$('#');
       //BOTTON DE LLENAR CAMPOS
       Gastos.llenado = $('#llenado');

        
        
    }

    static datos(){

        Gastos.Nombre.val("Martin RV");
        Gastos.Departamento.val("Sistemas");
        Gastos.Fecha.val("2013-05-30");
        Gastos.Rfc.val("MMA960729VE9");
        Gastos.Proveedor.val("1");
        Gastos.Telefono.val("3327280994");
        Gastos.Email.val("asesores@ae.com.mx");
        Gastos.Direccion.val("Villa California #1469");

    }

    static Validaciones (){

        let form = new FormData(Gastos.formulario[0]);
        let key,value;
        for([key,value] of form.entries())
            console.log("Nombre:"+key+" valor:"+value);

    }
    

    static main()
    {
    

        Gastos.init();
        Gastos.llenado.on("click", function(){
           // alert('aqui va llenado');
            Gastos.datos();
          });

        $('#enviargasto').on("click", function(){
           // Gastos.llenado();
           Gastos.Validaciones();
          });

       

    Gastos.contenedorClientes.on('click','.agregarContacto',function(){
        Gastos.areaAgregarContacto.append(
            '<div class="row form-group">'+
                    '<div class="col-md-1 text-center">'+ 
                        '<i class="fa fa-minus-circle text-red fa-3x borrarCliente" style="cursor:pointer;"></i>'+
                    '</div>'+
                    '<div class="col-md-5">'+
                        '<li>'+
                            '<div class="input-group">'+
                                '<div class="input-group-addon">'+
                                    '<i class="fa fa-product-hunt"></i>'+
                                '</div>'+
                                '<input class="form-control textoMay" type="text" name="regimen" required>'+
                            '</div>'+
                        '</li>'+
                    '</div>'+
                    '<div class="col-md-3">'+
                        '<div class="input-group">'+
                            '<div class="input-group-addon">'+
                                '<i class="fa fa-plus"></i>'+
                            '</div>'+
                            '<input class="form-control textoMay" type="number" name="nombre" required>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-3">'+
                        '<div class="input-group">'+
                            '<div class="input-group-addon">'+
                                '<i class="fa fa-phone"></i>'+
                            '</div>'+
                            '<input class="form-control textoMay" type="text" name="nombre" required>'+
                        '</div>'+
                    '</div>'+
            '</div>'
        );
    });

    Gastos.contenedorClientes.on('click','.borrarCliente',function(){
        $(this).parent().parent().remove();
    });

}
}

    Gastos.main();
    //alert("hola hola js ");


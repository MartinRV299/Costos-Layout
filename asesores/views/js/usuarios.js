class Usuarios{
    static init(){
        Usuarios.formulario = $('#formularioUsuarios');
        Usuarios.botonCancelar = $('#botonCancelar');
        Usuarios.contrasena = $('#contrasena');
        Usuarios.botonGenerarContrasena = $('#botonGenerarContrasena');
        Usuarios.expreg= Array(/[#<>"']{1,}/,
                            /[1-4]{1}/,
                            /[1-5]{1}/,
                            /[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}/,
                            /^[a-f1-6]{4}/);
        Usuarios.especiales = "' < > # \" ";
        Usuarios.validarIcono = $('.fa-check-circle');

        Usuarios.nombre = $('input[name="nombre"]');
        Usuarios.apellido1 = $('input[name="apellido1"]');
        Usuarios.apellido2 = $('input[name="apellido2"]');
        Usuarios.sucursal = $('select[name="sucursal"]');
        Usuarios.departamento = $('select[name="departamento"]');
        Usuarios.puesto = $('select[name="puesto"]');
        Usuarios.mail = $('input[name="mail"]');

        Usuarios.botonAdjuntar=$('#botonAdjuntar');
        Usuarios.nombreImagen=$('#nombreImagen');

        Usuarios.datosUsuario = $('.datosUsuarios');

        Usuarios.ventanaModal = $('#modalDatosUsuario');
        Usuarios.data = $('#datosUsuario');
    }

    static resetFormulario(){
        Usuarios.formulario[0].reset();
        Usuarios.validarIcono.removeClass("text-green");
        Usuarios.nombreImagen.text('Sin imagen.');
    }

    static validarFormulario(){
        if( Usuarios.expreg[0].test(Usuarios.nombre.val()) ) {
            Utilidades.alertaPersonalizada("warning","El campo nombre no puede tener caracteres especiales",Usuarios.especiales,60000,true);
            return false;
        }
        if( Usuarios.expreg[0].test(Usuarios.apellido1.val()) ) {
            Utilidades.alertaPersonalizada("warning","El primer apellido no puede tener caracteres especiales",Usuarios.especiales,60000,true);
            return false;
        }
        if( Usuarios.expreg[0].test(Usuarios.apellido2.val()) ) {
            Utilidades.alertaPersonalizada("warning","El segundo apellido no puede tener caracteres especiales",Usuarios.especiales,60000,true);
            return false;
        }
        if( !Usuarios.expreg[1].test(Usuarios.sucursal.val()) ) {
            Utilidades.alertaPersonalizada("warning","La sucursal no tiene el formato correcto","",60000,true);
            return false;
        }
        if( !Usuarios.expreg[2].test(Usuarios.departamento.val()) ) {
            Utilidades.alertaPersonalizada("warning","El departamento no tiene el formato correcto","",60000,true);
            return false;
        }
        if( !Usuarios.expreg[2].test(Usuarios.puesto.val()) ) {
            Utilidades.alertaPersonalizada("warning","El puesto no tiene el formato correcto","",60000,true);
            return false;
        }
        if( !Usuarios.expreg[3].test(Usuarios.mail.val()) ) {
            Utilidades.alertaPersonalizada("warning","El mail no tiene el formato correcto","",60000,true);
            return false;
        }
        if( !Usuarios.expreg[4].test(Usuarios.contrasena.val()) ) {
            Utilidades.alertaPersonalizada("warning","La contraseña no tiene el formato correcto","",60000,true);
            return false;
        }
        else
            return true;
    }

    static guardarFormulario(){
        let form = new FormData(Usuarios.formulario[0]);
        let key,value;
        for([key,value] of form.entries())
            console.log(key + ':' + value);
    }

    static generarContrasena(){
        let letras = Array('a','b','c','d','e','f');
        let numeros = Array('1','2','3','4','5','6');
        let contrasena='';
        for(let i=0;i<2;i++){
            contrasena += letras[Math.floor(Math.random() * letras.length)];
            contrasena += numeros[Math.floor(Math.random() * numeros.length)];
        }

        Usuarios.contrasena.val(contrasena);
        Usuarios.validarContrasena(Usuarios.contrasena,Usuarios.expreg[4]);
    }

    static validarContrasena(campo,expresion){
        if(!expresion.test(campo.val()))
            campo.parent().children('i').removeClass("text-green");
        else
            campo.parent().children('i').addClass("text-green");
    }

    static validarTexto(campo,expresion,longitud = 3){
        if(campo.val().length >= longitud){
            if(expresion.test(campo.val()))
                campo.parent().children('i').removeClass("text-green");
            else
                campo.parent().children('i').addClass("text-green");
        }
        else
            campo.parent().children('i').removeClass("text-green"); 
    }

    static validarSelect(campo,expresion){
        if(!expresion.test(campo.val()) && campo.val() === "")
            campo.parent().children('i').removeClass("text-green");
        else
            campo.parent().children('i').addClass("text-green");
    }

    static validarMail(campo,expresion){
        if(!expresion.test(campo.val()))
            campo.parent().children('i').removeClass("text-green");
        else
            campo.parent().children('i').addClass("text-green");
    }


    static main(){
        Usuarios.init();

        Usuarios.formulario.submit(function(e){
            e.preventDefault();
            if(Usuarios.validarFormulario())
                Usuarios.guardarFormulario();
        });

        Usuarios.botonCancelar.click(function(){
            Usuarios.resetFormulario();
        });

        Usuarios.botonGenerarContrasena.click(function(){
            Usuarios.generarContrasena();
        });

        Usuarios.nombre.keyup(function(){
            Usuarios.validarTexto($(this),Usuarios.expreg[0]);
        });

        Usuarios.apellido1.keyup(function(){
            Usuarios.validarTexto($(this),Usuarios.expreg[0]);
        });

        Usuarios.apellido2.keyup(function(){
            Usuarios.validarTexto($(this),Usuarios.expreg[0]);
        });

        Usuarios.sucursal.change(function(){
            Usuarios.validarSelect($(this),Usuarios.expreg[1]);
        });

        Usuarios.departamento.change(function(){
            Usuarios.validarSelect($(this),Usuarios.expreg[2]);
        });

        Usuarios.puesto.change(function(){
            Usuarios.validarSelect($(this),Usuarios.expreg[2]);
        });

        Usuarios.mail.keyup(function(){
            Usuarios.validarMail($(this),Usuarios.expreg[3]);
        });

        Usuarios.botonAdjuntar.change(function(e){
            if($(this).val() != ""){
                let file = e.target.files[0];

                let imagen = (/\.(?=jpg|jpeg|png)/gi).test(file.name);
                if(!imagen){
                    $(this).val('');
                    Utilidades.alertaPersonalizada("warning","Formato no valido","Debes incluir una imagen",60000,true);
                    return;
                }
                
                let tamanoMaximo = 2;
                if(file.size > tamanoMaximo * 1024 * 1024){
                    $(this).val('');
                    Utilidades.alertaPersonalizada("warning","El tamaño de la imagen es mayor al permitido","",60000,true);
                    return;
                }  

                let reader = new FileReader();
                reader.onload = function(e){
                    Swal.fire({
                        title: '',
                        text: '',
                        imageUrl: e.target.result,
                        imageWidth: 220,
                        imageHeight: 300,
                        imageAlt: 'Custom image',
                    });
                }
                reader.readAsDataURL(file);
                Usuarios.nombreImagen.text(file.name);

            }
            else
                Usuarios.nombreImagen.text('Sin imagen.');
        });
    }
}

Usuarios.main();

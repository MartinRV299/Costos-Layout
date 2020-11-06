class Utilidades{

    static alertaPersonalizada(tipo,titulo,subtitulo,tiempo,botonConfirmar = false){
        Swal.fire({
            title: titulo,
            text: subtitulo,
            type: tipo,
            timer: tiempo,
            showConfirmButton: botonConfirmar,
            allowOutsideClick: false
          });
    }

}
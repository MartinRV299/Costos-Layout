$(".visor-pdf-crow").click(function() {
   // $('.pdfobject-container').css({'height':'100vh','border':'1rem solid rgba(0,0,0,.1)' });
    mostrarVisorCrowPdf(this);
});

function mostrarVisorCrowPdf(e){

    let a = {
        pdf: "",
        cajaNegra: ""
    };
    a.pdf = $(e).attr("alt");
    a.cajaNegra = '<div class="modal-crow-pdf" id="modal-crow-pdf">' +
                        '<div class="canvas-crow-pdf precarga-crow-pdf___" id="targetPdf"></div>'+
                        '<span class="modal__boton-crow-pdf" id="modal__boton-crow-pdf"></span>' +
                  '</div>';
    $("body").append(a.cajaNegra);

    

    PDFObject.embed(a.pdf, "#targetPdf", options);

    $("#modal__boton-crow-pdf").css({ display: "none" });
    $("#modal__boton-crow-pdf").fadeIn(1E3);
 
    $("#modal__boton-crow-pdf").click(function() {
        $("#modal-crow-pdf").remove()
    });
    $("#modal-crow-pdf").click(function(b) {
        b.target === this && ($("#modal-crow-pdf").remove())
    });
   $(document).keyup(function(b) {
        27 == b.keyCode && ($(document).off("keyup"), $("#modal-crow").remove());
    });
}


let options = {
    pdfOpenParams: {
        pagemode: "thumbs",
        navpanes: 0,
        toolbar: 0,
        statusbar: 0,
        view: "FitV"
    }
};

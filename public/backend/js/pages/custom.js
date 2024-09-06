jQuery.event.special.touchstart = {
  setup: function( _, ns, handle ){
    if ( ns.includes("noPreventDefault") ) {
      this.addEventListener("touchstart", handle, { passive: false });
    } else {
      this.addEventListener("touchstart", handle, { passive: true });
    }
  }
};
$(document).ready(function(){
    $("input").attr("autocomplete", "off");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
function pageReload(){
    setTimeout(function() {
        location.reload(true);
    }, 3000);
}
function showToastMessage(toast_type = "success", message = "") {
    var toast_type = toast_type.toLowerCase();
    if(toast_type=="warning"){
        $.toast({
            heading: 'Warning',
            text: message,
            position: 'top-right',
            icon: 'warning',
            allowToastClose: false,
            stack: false
        });
    }else if(toast_type=="info"){
        $.toast({
            heading: 'Info',
            text: message,
            position: 'top-right',
            icon: 'info',
            allowToastClose: false,
            stack: false
        });
    }else if(toast_type=="error"){
        $.toast({
            heading: 'Error',
            text: message,
            position: 'top-right',
            icon: 'error',
            allowToastClose: false,
            stack: false,
            hideAfter: 4000,
        });
    }else{
        $.toast({
            heading: 'Success',
            text: message,
            position: 'top-right',
            icon: 'success',
            allowToastClose: false,
            stack: false,
            hideAfter: 3000,
        });
    }
}
function hideLoader() {
    $('#preloader').hide();
    $('#status').hide();
}

function showLoader() {
    $('#preloader').show();
    $('#status').show();
}
function language_check(){
    if(current_language == 'es'){
        return {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        };
    }else{
        return {
            "sEmptyTable":     "No data available in table",
            "sInfo":           "Showing _START_ to _END_ of _TOTAL_ entries",
            "sInfoEmpty":      "Showing 0 to 0 of 0 entries",
            "sInfoFiltered":   "(filtered from _MAX_ total entries)",
            "sInfoPostFix":    "",
            "sInfoThousands":  ",",
            "sLengthMenu":     "Show _MENU_ entries",
            "sLoadingRecords": "Loading...",
            "sProcessing":     "Processing...",
            "sSearch":         "Search:",
            "sZeroRecords":    "No matching records found",
            "oPaginate": {
                "sFirst":    "First",
                "sLast":     "Last",
                "sNext":     "Next",
                "sPrevious": "Previous"
            },
            "oAria": {
                "sSortAscending":  ": activate to sort column ascending",
                "sSortDescending": ": activate to sort column descending"
            }
        };
    }
}

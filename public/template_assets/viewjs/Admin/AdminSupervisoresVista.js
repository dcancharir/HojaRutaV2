let AdminSupervisoresVistaJS = function() {

    //
    // Setup module components
    //
    let _inicio=function() {
        let url = basePath + "ListarSupervisoresJson";
        $.ajax({
            url: url,
            type: "POST",
            contentType: "application/json",
            beforeSend: function () {
                block_general("body")
            },
            complete: function () {
                unblock("body");
            },
            success: function (response) {
                if(response.respuesta){
                    messageResponse({
                        message: response.mensaje,
                        type: "success"
                    });
                    let data=response.data;
                    let columns=columnasDatatable(data)
                    objetodatatable = $(".datatableSupervisores").DataTable({
                        "bDestroy": true,
                        "bSort": false,
                        "scrollCollapse": true,
                        "scrollX": false,
                        "sScrollX": "100%",
                        "paging": true,
                        "autoWidth": false,
                        "bAutoWidth": true,
                        "bProcessing": true,
                        "bDeferRender": true,
                        data: response.data,
                        columns:columns,
                        "initComplete": function (settings, json) {
                        },
                        "drawCallback": function (settings) {
                        }
                    });
                }
                else{
                    messageResponse({
                        message: response.mensaje,
                        type: "error"
                    });
                }
            },
            error: function (xmlHttpRequest, textStatus, errorThrow) {
                console.log("errorrrrrrrr");
            }
        });
    }
    let columnasDatatable = function (data){
        let obj=[
            {
                title: "Usuario"
            },
            {
                title: "Nombres",
            },
            {
                title: "Estado",
            },
            {
                title: "Acciones",
            },
        ]
        if(data){
            if(data.length>0){
                obj=[
                    {
                        data: "usuario",
                        title: "Usuario",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "nombres",
                        title: "Nombres",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "estado",
                        title: "Estado",
                        "className":'font-weight-semibold mb-0',
                        "render":function (value, type, oData, meta) {
                            return oData.estado==1?'Activo':'Inactivo';

                        }
                    },
                    {
                        data: null,
                        title: "Acciones",
                        "className":'font-weight-semibold mb-0',
                        "render":function(value, type, oData, meta){
                            return ""
                        }
                    },

                ]
            }
        }
        return obj
    }
    let _actions=function(){

    }
     return {
        init: function() {
            _inicio();
            _actions();
        }
    }
}();


// // Initialize module
// // ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    AdminSupervisoresVistaJS.init();
});

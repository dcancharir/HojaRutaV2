let TiendaVistaJS = function() {
    //
    // Setup module components
    //
    let inicio=function() {
        let url = basePath + "ListarTiendasporSupervisorJson";
        $.ajax({
            url: url,
            type: "POST",
            contentType: "application/json",
            beforeSend: function () {
                block_general("body")
            },
            complete: function () {
                unblock("body")
            },
            success: function (response) {
                if(response.respuesta){
                    messageResponse({
                        message: response.mensaje,
                        type: "success"
                    });
                    let columns=columnasDatatable(response.data)
                    objetodatatable = $(".datatable-tienda").DataTable({
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
                title: "CC"
            },
            {
                title: "Nombre",
            },
            {
                title: "Direccion",
            },
            {
                title: "Frecuencia Semanal",
            },
            {
                title: "Nro. Visitas",
            },
            {
                title: "Acciones",
            },
        ]
        if(data){
            if(data.length>0){
                obj=[
                    {
                        data: "cc",
                        title: "CC",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "nombres",
                        title: "Nombre",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "direccion",
                        title: "Dirección",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "frecuencia_semanal",
                        title: "Frecuencia Semanal",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "nro_visitas_semana",
                        title: "Nro. Visitas",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: null,
                        title:"Acciones",
                        "bSortable": false,
                        "render": function (value, type, oData, meta) {
                            let botones =  `<button type="button" class="btn btn-sm btn-danger btn_ventas"
                                                data-id=${oData.tienda_id}>Ver Ventas</button>`;
                            return botones;
                        }
                    }

                ]
            }
        }
        return obj
    }
    let acciones=function(){
        $(document).on("click", ".btn_ventas", function() {
            tienda_id=$(this).data("id");
            // window.location.href = basePath + site;
            redirect("Venta/"+tienda_id);
        });
        $(document).on("click", ".btn_recargar", function() {
            window.location.reload()
        });
        $(document).on("click", "#btn_pdf", function() {
            window.open(basePath+ 'TiendaExportarPdf','_blank');

        });
        $(document).on("click", "#btn_excel", function() {
            redirect("TiendaExportarExcel");
        });
    }
    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            inicio();
            acciones();
        }
    }
}();


// // Initialize module
// // ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    TiendaVistaJS.init();
});

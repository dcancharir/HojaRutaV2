let TiendaVistaJS = function() {
    //
    // Setup module components
    //
    let _inicio=function() {
        let url = basePath + "ListarTiendasporSupervisorJson";
        $.ajax({
            url: url,
            type: "POST",
            contentType: "application/json",
            beforeSend: function () {
                // $.LoadingOverlay("show");
            },
            complete: function () {
                // $.LoadingOverlay("hide");
            },
            success: function (response) {
                if(response.respuesta){
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
    let _actions=function(){
        $(document).on("click", ".btn_ventas", function() {
            tienda_id=$(this).data("id");
            // window.location.href = basePath + site;
            redirect("Venta/"+tienda_id);
        });
    }
    // Line chart
    // let _googleMapBasic = function() {
    //     if (typeof google == 'undefined') {
    //         console.warn('Warning - Google Maps library is not loaded.');
    //         return;
    //     }
    //     // Map settings
    //     function initialize() {
    //         // Define map element
    //         let map_basic_element = document.getElementById('map');
    //         // Optinos
    //         let mapOptions = {
    //             zoom: 12,
    //             center: new google.maps.LatLng(47.496, 19.037)
    //         };
    //         // Apply options
    //         let map = new google.maps.Map(map_basic_element, mapOptions);
    //     }
    //     // Load map
    //     google.maps.event.addDomListener(window, 'load', initialize);
    // };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _inicio();
            _actions();
            // _googleMapBasic();
        }
    }
}();


// // Initialize module
// // ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    TiendaVistaJS.init();
});

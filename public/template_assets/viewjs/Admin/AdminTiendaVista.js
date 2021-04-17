let AdminTiendaVistaJS = function() {

    //
    // Setup module components
    //
    let _inicio=function() {
        let url = basePath + "ListarTiendasJson";
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
        ]
        if(data){
            if(data.length>0){
                let dias=[1,2,3,4,5,6,7];
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
                        data: null,
                        title: "Frecuencia Semanal",
                        "className":'font-weight-semibold mb-0',
                        "render":function(value, type, oData, meta){
                            let span=`<select style="width:100%;" class="form-select form-select-sm" aria-label=".form-select-sm example">`;
                            $.each(dias, function(index,val){
                                if(val==oData.frecuencia_semanal){
                                    span+=`<option selected="selected" value="${val}">${val}</option>`;
                                }else{
                                    span+=`<option value="${val}">${val}</option>`;
                                }
                            })
                            span+=`</select>`;
                            return span;
                        }
                    },
                    {
                        data: "nro_visitas_semana",
                        title: "Nro. Visitas",
                        "className":'font-weight-semibold mb-0'
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
    AdminTiendaVistaJS.init();
});

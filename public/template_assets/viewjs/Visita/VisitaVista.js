let VisitaVistaJS=function(){

    let _inicio=function(){
        let fechaHoy = moment(new Date()).format('YYYY-MM-DD');
        $("#fechaInicio").val(fechaHoy);
        $("#fechaFin").val(fechaHoy);

        $("#fechaInicio").datetimepicker({
            format: 'YYYY-MM-DD',
            ignoreReadonly: true,
            allowInputToggle: true,
        });

        $("#fechaFin").datetimepicker({
            format: 'YYYY-MM-DD',
            ignoreReadonly: true,
            allowInputToggle: true,
            useCurrent: false,
        });
        listarTiendas()
        // let url=basePath+'listarVisitasporSupervisorJson';

        // $.ajax({
        //     url:url,
        //     contentType:"application/json",
        //     type:"POST",
        //     beforeSend:function () {
        //         block_general("body")
        //     },
        //     complete:function () {
        //         unblock("body");
        //     },
        //     success:function (response) {
        //         console.log(response);
        //         if(response.respuesta){
        //             let columns=columnasDatatable(response.data)
        //             objetodatatable = $(".datatableVisita").DataTable({
        //                 "bDestroy": true,
        //                 "bSort": false,
        //                 "scrollCollapse": true,
        //                 "scrollX": false,
        //                 "sScrollX": "100%",
        //                 "paging": true,
        //                 "autoWidth": false,
        //                 "bAutoWidth": true,
        //                 "bProcessing": true,
        //                 "bDeferRender": true,
        //                 data: response.data,
        //                 columns:columns,
        //                 "initComplete": function (settings, json) {
        //                 },
        //                 "drawCallback": function (settings) {
        //                 }
        //             });
        //         }
        //     },
        //     error:function (xmlHttpRequest, textStatus, errorThrow) {
        //         console.log(errorThrow);
        //     }
        // })
    }
    let _componentes=function(){
        $("#fechaInicio").on("dp.change", function (e) {
            $('#fechaFin').data("DateTimePicker").minDate(e.date);
        });
        $("#fechaFin").on("dp.change", function (e) {
            $('#fechaInicio').data("DateTimePicker").maxDate(e.date);
        });
        $(document).on('click','#btnBuscar',function (e) {
            e.preventDefault()
            let fechaInicio=$("#fechaInicio").val();
            let fechaFin=$("#fechaFin").val();
            let arrayTiendas=$("#cboTienda").val();
            let addTable=$("#contenedorTable");
            let dataForm={
                fechaInicio: fechaInicio,
                fechaFin: fechaFin,
                arrayTiendas: arrayTiendas
            }
            let url=basePath+'ListarVisitasporTienda';

            $.ajax({
                url:url,
                contentType:"application/json",
                type:"POST",
                data:JSON.stringify(dataForm),
                beforeSend:function () {
                    block_general("body")
                },
                complete:function () {
                    unblock("body");
                },
                success:function (response) {
                    if(response.respuesta){
                        messageResponse({
                            message: response.mensaje,
                            type: "success"
                        });
                        addTable.empty();
                        $(addTable).append(`<div class="card">
                                                <div class="card-body">
                                                    <table id="datatableVisita" class="table table-xs table-hover" style="width:100%">
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>`);
                        let columns=columnasDatatable(response.data)
                        objetodatatable = $("#datatableVisita").DataTable({
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
                error:function (xmlHttpRequest, textStatus, errorThrow) {
                    console.log(errorThrow);
                }
            })
        })
    }
    let listarTiendas=function(){
        $.ajax({
            type: "POST",
            url: basePath + "ListarTiendasporSupervisorJson",
            cache: false,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            beforeSend: function (xhr) {
                block_general('body')
            },
            success: function (result) {
                let data = result.data;

                $.each(data, function (index, value) {
                    $("#cboTienda").append(`<option value="${value.tienda_id}">${value.nombres}</option>`);
                });
                $("#cboTienda").select2({
                    multiple: true, placeholder: "-- Seleccione Tienda --"
                });
                $("#cboTienda").val(null).trigger("change");
            },
            error: function (request, status, error) {
               console.log(error);
            },
            complete: function (resul) {
                unblock('body')
            }
        });
        return false;
    }
    let columnasDatatable = function (data){
        let obj=[
            {
                title: "Fecha Visita"
            },
            {
                title: "Tienda",
            },
            {
                title: "CC",
            },
            {
                title: "Latitud",
            },
            {
                title: "Longitud",
            },
            {
                title: "Acciones",
            },
        ]
        if(data){
            if(data.length>0){
                obj=[
                    {
                        data: "fecha_visita",
                        title: "Fecha Visita",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "tienda.nombres",
                        title: "Tienda",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "tienda.cc",
                        title: "CC",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "latitud",
                        title: "Latitud Supervisor",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "longitud",
                        title: "Longitud Supervisor",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: null,
                        title:"Acciones",
                        "bSortable": false,
                        "render": function (value, type, oData, meta) {
                            let botones =  `<button type="button" class="btn btn-sm btn-danger btnDetalle"
                                                data-id=${oData.visita_id}>Detalle</button>`;
                            return botones;
                        }
                    }

                ]
            }
        }
        return obj
    }
    return{
        init:function(){
            _inicio()
            _componentes()
        }
    }
}()
document.addEventListener("DOMContentLoaded",function(){
    VisitaVistaJS.init()
})

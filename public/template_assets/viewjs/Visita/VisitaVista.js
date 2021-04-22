let VisitaVistaJS=function(){

    let inicio=function(){
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
    }
    let acciones=function(){
        $("#fechaInicio").on("dp.change", function (e) {
            $('#fechaFin').data("DateTimePicker").minDate(e.date);
        });
        $("#fechaFin").on("dp.change", function (e) {
            $('#fechaInicio').data("DateTimePicker").maxDate(e.date);
        });
        $(document).on("click", ".btn_recargar", function() {
            window.location.reload()
        });
        $(document).on('click','#btnPdfRespuestas',function(){
            let visita_id=$("#visita_id").val();
            window.open(basePath+ 'RespuestasporVisitaExportarPdf/'+visita_id,'_blank');
        });
        $(document).on("click", "#btnExcelRespuestas", function() {
            let visita_id=$("#visita_id").val();
            redirect("RespuestasporVisitaExportarExcel/"+visita_id);
        });
        $(document).on("click",'#btnCerrarModal',function(e){
            e.preventDefault()
            $("#modalRespuestas").modal('hide')
        })

        $(document).on('click','#btnBuscar',function (e) {
            e.preventDefault()
            let fechaInicio=$("#fechaInicio").val()+' 00:00:00';
            let fechaFin=$("#fechaFin").val()+' 23:59:59';
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
        $(document).on("click",'.btnDetalle',function(e){
            e.preventDefault();
            let visita_id=$(this).data('visitaid');
            let nombreTienda=$(this).data('tiendanombres');
            let url=basePath+'ObtenerVisitaIdJson';
            var dataForm={
                visita_id:visita_id
            };
            let anexarPreguntaCajero=$("#responsabilidadCajero");
            let anexarPreguntaSupervisor=$("#responsabilidadSupervisor");
            anexarPreguntaCajero.html("");
            anexarPreguntaSupervisor.html("");

            $.ajax({
                url:url,
                contentType: 'application/json',
                type: 'POST',
                data:JSON.stringify(dataForm),
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
                        let data = response.data;
                        let respuestas=data.respuestas;
                        let textoPreguntaCajero="";
                        let textoPreguntaSupervisor="";
                        let indicePreguntaCajero=1;
                        let indicePreguntaSupervisor=1;
                        $.each(respuestas,function(index,value){
                            if(value.pregunta.tipo==1){
                                textoPreguntaCajero+='<h6 class="font-weight-semibold">'+indicePreguntaCajero+'.- '+value.pregunta.titulo+'</h6><p>Respuesta: '+value.opcion.opcion+'</p><p>Observación: '+value.observacion+'</p><hr>';
                                indicePreguntaCajero++;
                            }
                            else{
                                textoPreguntaSupervisor+='<h6 class="font-weight-semibold">'+indicePreguntaSupervisor+'.- '+value.pregunta.titulo+'</h6><p>Respuesta: '+value.opcion.opcion+'</p><p>Observación: '+value.observacion+'</p><hr>';
                                indicePreguntaSupervisor++;
                            }
                        })
                        anexarPreguntaCajero.append(textoPreguntaCajero);
                        anexarPreguntaSupervisor.append(textoPreguntaSupervisor);
                        $("#visita_id").val(visita_id);
                        $("#nombreTienda").text(nombreTienda);
                        $("#modalRespuestas").modal("show");
                    }
                    else{
                        messageResponse({
                            message: response.mensaje,
                            type: "error"
                        });
                    }
                },
                error: function (xmlHttpRequest, textStatus, errorThrow) {
                    console.log(errorThrow)
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
                                                data-visitaid="${oData.visita_id}"
                                                data-tiendanombres="${oData.tienda.nombres}"
                                                >Detalle</button>`;
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
            inicio()
            acciones()
        }
    }
}()
document.addEventListener("DOMContentLoaded",function(){
    VisitaVistaJS.init()
})

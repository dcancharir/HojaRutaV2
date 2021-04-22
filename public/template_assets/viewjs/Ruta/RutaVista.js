let RutaVistaJS=function(){
    let dataTable;
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
    }
    let acciones=function(){
        $("#fechaInicio").on("dp.change", function (e) {
            $('#fechaFin').data("DateTimePicker").minDate(e.date);
        });
        $("#fechaFin").on("dp.change", function (e) {
            $('#fechaInicio').data("DateTimePicker").maxDate(e.date);
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
        $(document).on("click", ".btn_recargar", function() {
            window.location.reload()
        });
        $(document).on('click','#btnBuscar',function (e) {
            e.preventDefault();
            let addTable=$("#contenedorTable");
            let fechaInicio = $("#fechaInicio").val()+' 00:00:00'
            let fechaFin = $("#fechaFin").val()+' 23:59:59'
            let dataForm={
                fechaInicio: fechaInicio,
                fechaFin:fechaFin,
            }
            let url = basePath + "RutaListarporUsuarioJson";
            $.ajax({
                url: url,
                type: "POST",
                contentType: "application/json",
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
                        addTable.empty();
                        $(addTable).append(`<div class="card">
                                                <div class="card-body">
                                                    <table id="datatableRuta" class="table table-sm table-xs table-hover" style="width:100%">
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>`);
                        let columns=columnasDatatable(response.data)
                        dataTable = $("#datatableRuta").DataTable({
                            "bDestroy": true,
                            "bSort": false,
                            // "scrollCollapse": true,
                            // "scrollX": false,
                            // "sScrollX": "100%",
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
                    }else{
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

         // Add event listener for opening and closing details
        $(document).on('click', 'td.details-control', function (e) {
            e.preventDefault()
            let tr = $(this).closest('tr');
            let ruta_id=tr.find('a').data("id");
            let url = basePath + "ListarDetalleRutaporRutaIdJson";
            let row = dataTable.row( tr );

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');

                tr.find('a').removeClass('border-danger-400 text-danger-400');
                tr.find('a').addClass('border-success-400 text-success-400');
                tr.find('a>i').removeClass("icon-chevron-down");
                tr.find('a>i').addClass("icon-chevron-right");
            }
            else {
                $.ajax({
                    url: url,
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({ruta_id:ruta_id}),
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
                             // Open this row
                            row.child( cargarDetalle(data) ).show();
                            tr.addClass('shown');

                            tr.find('a').removeClass('border-success-400 text-success-400');
                            tr.find('a').addClass('border-danger-400 text-danger-400');
                            tr.find('a>i').removeClass("icon-chevron-right");
                            tr.find('a>i').addClass("icon-chevron-down");

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


        } );
    }
    let columnasDatatable = function (data){
        let obj=[
            {
                tittle:"Detalle"
            },
            {
                title: "Fecha Creación"
            },
            {
                title: "Estado",
            },
            {
                title: "Tiendas Pendientes",
            },
            {
                title: "Tiendas Visitadas",
            },
            {
                title: "Tipo Ruta",
            },
        ]
        if(data){
            if(data.length>0){
                obj=[
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": "ruta_id",
                        "title": "Detalle",
                        "defaultContent": '',
                        "render": function (value,type,oData,meta) {
                            var span = '';
                            span = `<a href="#" data-id="${oData.ruta_id}"class="btn btn-sm bg-transparent border-success-400 text-success-400 rounded-round border-2 btn-icon">
                                        <i class="icon-chevron-right"></i>
                                    </a>`;
                            return span;
                        },
                        width: "50px"
                    },
                    {
                        data: "fecha",
                        title: "Fecha Creación",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: null,
                        title: "Estado",
                        // "className":'font-weight-semibold mb-0',
                        "render":function(value,type,oData,meta){
                            let clase="";
                            if(oData.estado_completo==1){
                                clase="success";
                            }
                            else{
                                clase="danger";
                            }
                            return `<h6 class="text-${clase}-600">${oData.estado_completo==1?"Terminado":"Pendiente"}</h6>`;
                        }
                    },
                    {
                        data: "tiendas_pendientes",
                        title: "Tiendas Pendientes",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "tiendas_visitadas",
                        title: "Tiendas Visitadas",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: null,
                        title: "Tipo Ruta",
                        "render":function(value, type, oData, meta){
                            return `<h6 class="font-weight-semibold mb-0">${oData.tipo_ruta==1?"Sistema":"Manual"}</h6>`;
                        }
                    },
                ]
            }
        }
        return obj
    }
    let cargarDetalle=function ( data ) {
        // `d` is the original data object for the row

        let arrayTemplateDetalles=[];
        if(data.length==0||data==null){
            return '';
        }
        $.each(data,function(index,value){
            let clase="";
            let claseVisitado="";
            let td='';
            if(value.visita_id){
                td=`<td align="center">
                            <a href="javascript:void(0);" class="btn btn-sm bg-danger-400 btnDetalle"
                            data-rutaid="${value.ruta_id}"
                            data-detallerutaid="${value.detalle_ruta_id}"
                            data-visitaid="${value.visita_id}"
                            data-tiendanombres="${value.tienda.nombres}"
                            >Detalle</a>
                        </td>`;
                claseVisitado="success";
            }
            else{
                td=`<td></td>`;
                claseVisitado="danger";
            }
            if(value.tipo_detalle==1){
                clase="bg-indigo";
            }
            else{
                clase="bg-blue";
            }

            arrayTemplateDetalles.push(`
                <tr>
                    <td>
                        ${value.orden}
                    </td>
                    <td><span class="">${value.tienda.nombres}</span></td>
                    <td><span class="">${value.tienda.direccion}</td>
                    <td><h6 class="text-success-600 badge ${clase}">${value.tipo_detalle==1?"Montos de Venta":"Frecuencia Semanal"}</h6></td>
                    <td><span class="text-muted">${value.observacion}</span></td>
                    <td><span class="font-weight-semibold mb-0 text-${claseVisitado}-600">${value.visita_id==null?"No visitado":"Visitado"}</span></td>
                    ${td}
                </tr>`);
        })
        let template=`<div class="table-responsive">
                        <table class="table table-xs table-striped table-bordered" id="tableDetalleRuta" >
                            <thead>
                                <tr>
                                    <th>Orden</th>
                                    <th>Tienda</th>
                                    <th>Dirección</th>
                                    <th>Tipo</th>
                                    <th>Observación</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                               ${arrayTemplateDetalles.join('')}
                            </tbody>
                        </table>
                    </div>`;
        return template;
    }
    return{
        init:function() {
            inicio()
            acciones()
        }
    }
}()
document.addEventListener('DOMContentLoaded', function() {
    RutaVistaJS.init();
})


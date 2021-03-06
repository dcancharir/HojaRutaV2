let ReporteEfectividadJS=function(){
    let dataTable;
    let chartimg;
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
        $(document).on("click", ".btnMostrarChart", function() {
            $("#modalChart").modal("show");
        });
        $(document).on("click", ".btn_recargar", function() {
            window.location.reload()
        });
        $(document).on("click",'#btnBuscar',function (e) {
            e.preventDefault()
            let fechaInicio= $("#fechaInicio").val()+' 00:00:00';
            let fechaFin= $("#fechaFin").val()+' 23:59:59';
            let dataForm={
                fechaInicio: fechaInicio,
                fechaFin: fechaFin
            }
            let url = basePath + "ReporteEfectividadJson";
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
                        let addTable=$("#contenedorTable");
                        let botonChart=$(".btnMostrarChart");
                        if(response.data.length>0){
                            messageResponse({
                                message: response.mensaje,
                                type: "success"
                            });
                            let data=response.data;
                            let dias=[];
                            let porcentajes=[];
                            let efectividad=0;
                            let fechaChart='';
                            $.each(data,function(index,value){
                                fechaChart= moment(value.fecha).format('YYYY-MM-DD');
                                dias.push(fechaChart);
                                efectividad=(value.tiendas_visitadas*100)/(value.tiendas_visitadas+value.tiendas_pendientes);
                                porcentajes.push(efectividad.toFixed(2));
                            })
                            //Invertir arrays
                            dias.reverse();
                            porcentajes.reverse();
                            //Llenar chart con data
                            cargarChart(dias,porcentajes);
                            $("#modalChart").modal("show");
                            botonChart.show();

                            //creacion de Datatable
                            addTable.empty();
                            $(addTable).append(`<div class="card">
                                                    <div class="card-body">
                                                        <table id="datatableEfectividad" class="table table-xs table-hover" style="width:100%">
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>`);
                            let columns=columnasDatatable(data)
                            objetodatatable = $("#datatableEfectividad").DataTable({
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
                        }else{
                            messageResponse({
                                message: "No se encontraron registros",
                                type: "warning"
                            });
                        }
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
        $(document).on('click','#btn_pdf',function (e) {
            e.preventDefault();
            let fechaInicio= $("#fechaInicio").val()+' 00:00:00';
            let fechaFin= $("#fechaFin").val()+' 23:59:59';
            let hidden_html=$("#chart").html()
            let dataForm={
                fechaInicio: fechaInicio,
                fechaFin: fechaFin,
                hidden_html:hidden_html
            }
            let url = basePath + "ReporteEfectividadExportarPdf";
            $.ajax({
                url: url,
                type: "POST",
                contentType: "application/json",
                // processData: false,
                data:JSON.stringify(dataForm),
                xhrFields: {
                    responseType: 'blob'
                },
                beforeSend: function () {
                    block_general("body")
                },
                complete: function () {
                    unblock("body");
                },
                success: function (response, status, xhr) {
                    let filename = "";

                    let disposition = xhr.getResponseHeader('Content-Disposition');

                     if (disposition) {
                        let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        let matches = filenameRegex.exec(disposition);
                        if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                    }

                    let blob = new Blob([response]);
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    link.click();
                },
                error: function (xmlHttpRequest, textStatus, errorThrow) {
                    console.log("errorrrrrrrr");
                }
            })
        })
        $(document).on('click','#btn_excel',function (e) {
            e.preventDefault();
            let fechaInicio= $("#fechaInicio").val()+' 00:00:00';
            let fechaFin= $("#fechaFin").val()+' 23:59:59';
            let hidden_html=$("#chart").html()
            let dataForm={
                fechaInicio: fechaInicio,
                fechaFin: fechaFin,
                hidden_html:hidden_html
            }
            let url = basePath + "ReporteEfectividadExportarExcel";
            $.ajax({
                url: url,
                type: "POST",
                contentType: "application/json",
                // processData: false,
                data:JSON.stringify(dataForm),
                xhrFields: {
                    responseType: 'blob'
                },
                beforeSend: function () {
                    block_general("body")
                },
                complete: function () {
                    unblock("body");
                },
                success: function (response, status, xhr) {
                    let filename = "";

                    let disposition = xhr.getResponseHeader('Content-Disposition');

                     if (disposition) {
                        let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        let matches = filenameRegex.exec(disposition);
                        if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                    }

                    let blob = new Blob([response]);
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    link.click();
                },
                error: function (xmlHttpRequest, textStatus, errorThrow) {
                    console.log("errorrrrrrrr");
                }
            })
        })
    }
    let cargarChart=function(dias,porcentajes){
        $("#modalChart").on("shown.bs.modal", function () {
            if (typeof echarts == 'undefined') {
                console.warn('Warning - echarts.min.js is not loaded.');
                return;
            }
            // Define elements
            let line_basic_element = document.getElementById('lineBasic');
           // Initialize chart
           let line_basic = echarts.init(line_basic_element);
           //
           // Chart config
           //
           // Options
           line_basic.setOption({
               // Define colors
               color: ['#EF5350', '#66BB6A'],
               // Global text styles
               textStyle: {
                   fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                   fontSize: 13
               },
               // Chart animation duration
               animationDuration: 750,
               // Setup grid
               grid: {
                   left: 0,
                   right: 40,
                   top: 35,
                   bottom: 0,
                   containLabel: true
               },
               // Add legend
               legend: {
                   data: ['Efectividad'],
                   itemHeight: 8,
                   itemGap: 20
               },
               // Add tooltip
               tooltip: {
                   trigger: 'axis',
                   backgroundColor: 'rgba(0,0,0,0.75)',
                   padding: [10, 15],
                   textStyle: {
                       fontSize: 13,
                       fontFamily: 'Roboto, sans-serif'
                   }
               },
               // Horizontal axis
               xAxis: [{
                   type: 'category',
                   boundaryGap: false,
                   data: dias,
                   axisLabel: {
                       color: '#333'
                   },
                   axisLine: {
                       lineStyle: {
                           color: '#999'
                       }
                   },
                   splitLine: {
                       lineStyle: {
                           color: ['#eee']
                       }
                   }
               }],
               // Vertical axis
               yAxis: [{
                   type: 'value',
                   axisLabel: {
                       formatter: '{value} %',
                       color: '#333'
                   },
                   axisLine: {
                       lineStyle: {
                           color: '#999'
                       }
                   },
                   splitLine: {
                       lineStyle: {
                           color: ['#eee']
                       }
                   },
                   splitArea: {
                       show: true,
                       areaStyle: {
                           color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
                       }
                   }
               }],
               // Add series
               series: [
                   {
                       name: 'Efectividad',
                       type: 'line',
                       data: porcentajes,
                       smooth: true,
                       symbolSize: 7,
                       markLine: {
                           data: [{
                               type: 'average',
                               name: 'Average'
                           }]
                       },
                       itemStyle: {
                           normal: {
                               borderWidth: 2
                           }
                       }
                   }
               ]
           });

            // chartimg=src;
            line_basic.on('finished', function () {
                chartimg=document.getElementById('chart');
                let src = line_basic.getDataURL({
                    pixelRatio: 2,
                    backgroundColor: '#fff'
                });
                chartimg.innerHTML=`<img src="${src}">`
            });
        });
    }
    let columnasDatatable = function (data){
        let obj=[
            {
                title: "Fecha Creacion Ruta"
            },
            {
                title: "Tiendas Visitadas",
            },
            {
                title: "Tiendas Pendientes",
            },
            {
                title: "Efectividad",
            },
        ]
        if(data){
            if(data.length>0){
                obj=[
                    {
                        data: "fecha",
                        title: "Fecha Creacion Ruta",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "tiendas_visitadas",
                        title: "Tiendas Visitadas",
                        "className":'font-weight-semibold mb-0 text-right'
                    },
                    {
                        data: "tiendas_pendientes",
                        title: "Tiendas Pendientes",
                        "className":'font-weight-semibold mb-0 text-right'
                    },
                    {
                        data: null,
                        title:"Efectividad",
                        "bSortable": false,
                        "className":'font-weight-semibold mb-0 text-right',
                        "render": function (value, type, oData, meta) {
                            let tiendaspendientes=oData.tiendas_pendientes;
                            let tiendasvisitadas=oData.tiendas_visitadas;
                            let efectividad=0;
                            let tiendasfijas=tiendaspendientes+tiendasvisitadas;
                            efectividad=parseFloat((tiendasvisitadas*100)/tiendasfijas);
                            return efectividad.toFixed(2)+"%";
                        }
                    }

                ]
            }
        }
        return obj
    }
    return{
        init:function() {
            inicio()
            acciones()
        }
    }
}()
document.addEventListener('DOMContentLoaded', function() {
    ReporteEfectividadJS.init();
})

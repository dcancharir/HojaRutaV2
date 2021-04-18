let AdminTiendaVistaJS = function() {

    //
    // Setup module components
    //
    let arrayFrecuenciaSemanal=[]
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
                    messageResponse({
                        message: response.mensaje,
                        type: "success"
                    });
                    arrayFrecuenciaSemanal.length=0;
                    let data=response.data;
                    $.each(data,function(i,v){
                        arrayFrecuenciaSemanal.push({
                            tienda_id:v.tienda_id,
                            frecuencia_semanal:v.frecuencia_semanal
                        })
                    })
                    let columns=columnasDatatable(data)
                    objetodatatable = $(".datatableTienda").DataTable({
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
                            let span=`<select style="width:100%;" class="browser-default custom-select selectFrecuenciaSemanal select${oData.tienda_id}" data-id="${oData.tienda_id}">`;
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
        $(document).on('change','.selectFrecuenciaSemanal',function(e){
            e.preventDefault()
            let tienda_id=$(this).data('id')
            let frecuencia_semanal=$(this).val()
            let dataForm={
                tienda_id:tienda_id,
                frecuencia_semanal:frecuencia_semanal
            }
            let url = basePath + "EditarFrecuenciaSemanalTiendaJson";
            let frecuenciaAnterior=arrayFrecuenciaSemanal.filter(x=>x.tienda_id==tienda_id)

            messageConfirmation({
                title: 'Confirmacion',
                content: 'Seguro que desea editar el registro?',
                callBackSAceptarComplete: function() {
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
                                arrayFrecuenciaSemanal.map(function(dato){
                                    if(dato.tienda_id == frecuenciaAnterior[0].tienda_id){
                                      dato.frecuencia_semanal = frecuencia_semanal;
                                    }
                                    return dato;
                                });
                            }
                            else{
                                messageResponse({
                                    message: response.mensaje,
                                    type: "error"
                                });
                                $('.select'+tienda_id).val(frecuenciaAnterior[0].frecuencia_semanal);
                            }
                        },
                        error: function (xmlHttpRequest, textStatus, errorThrow) {
                            console.log("errorrrrrrrr");
                            $('.select'+tienda_id).val(frecuenciaAnterior[0].frecuencia_semanal);
                        }
                    });
                },
                callBackSCCerraromplete:function(){
                    $('.select'+tienda_id).val(frecuenciaAnterior[0].frecuencia_semanal);
                }
            })


        })
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

let AdminRoleVistaJS = function() {
    let inicio=function() {
        let url = basePath + "ListarRoleJson";
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
                    objetodatatable = $(".datatableRole").DataTable({
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
                title: "Rol"
            },
            {
                title: "Guard",
            },
            {
                title: "Fecha Creación",
            },
            {
                title: "Fecha Actualización",
            },
            {
                title: "Acciones",
            },
        ]
        if(data){
            if(data.length>0){
                obj=[
                    {
                        data: "name",
                        title: "Rol",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "guard_name",
                        title: "Guard",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "created_at",
                        title: "Fecha Creación",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: 'updated_at',
                        title: "Fecha Modificacion",
                        "className":'font-weight-semibold mb-0',
                    },
                    {
                        data: null,
                        title: "Acciones",
                        'render':function(value, type, oData, meta){
                            let span=`<a href="javascript:void(0);" class="btn btn-sm bg-danger-400 btnEditar"
                            data-id="${oData.id}"
                            data-name="${oData.name}"
                            >Editar</a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-outline-danger btnEliminar"
                            data-id="${oData.id}"
                            >Eliminar</a>`;
                            return span;
                        }
                    },
                ]
            }
        }
        return obj
    }
    let acciones=function(){
        $(document).on('click','#btnGuardar',function(e){


            let dataForm=$("#frmRoleForm").serializeFormJSON()
            let role_id=$("#role_id").val()
            let name=$("#name").val()
            if(name==''){
                messageResponse({
                    message: 'El nombre no puede ser una cadena vacía',
                    type: "warning"
                });
                return false;
            }
            let url=basePath;
            if(role_id==0){
                url+='GuardarRoleJson'
            }
            else{
                url+='ActualizarRoleJson'
            }
            $.ajax({
                type: "POST",
                contentType: "application/json",
                data:JSON.stringify(dataForm),
                url: url,
                success: function (response){
                    if(response.respuesta){
                        messageResponse({
                            message: response.mensaje,
                            type: "success"
                        });
                        $("#modalRole").modal('hide');
                        setTimeout(function () {
                            window.location.reload();
                        },2500)
                    }
                    else{
                        messageResponse({
                            message: response.mensaje,
                            type: "error"
                        });
                    }
                },
                beforeSend: function () {
                    block_general('body')
                },
                complete: function () {
                    unblock('body')
                },
                error: function (xmlHttpRequest, textStatus, errorThrow) {
                    console.error(errorThrow)
                }
            })
        })
        $(document).on('click','.btnEditar',function(e) {
            e.preventDefault()
            let name=$(this).data('name')
            let role_id=$(this).data('id')
            $("#titulo").text('Editar Registro')
            $("#role_id").val(role_id)
            $('#name').val(name)
            $("#modalRole").modal('show')
        })
        $(document).on('click','.btnNuevo',function(e){
            e.preventDefault()
            $("#titulo").text('Agregar nuevo Rol')
            $("#role_id").val(0);
            $("#name").val('');
            $("#modalRole").modal('show');
        })
        $(document).on('click','.btnEliminar',function(e){
            e.preventDefault()
            let role_id=$(this).data('id')
            let dataForm ={
                role_id: role_id
            }
            let url=basePath+'EliminarRoleJSon'
            messageConfirmation({
                title: 'Confirmación',
                content: '¿Seguro que desea eliminar el registro?',
                callBackSAceptarComplete: function() {
                    $.ajax({
                        type: "POST",
                        contentType: "application/json",
                        data:JSON.stringify(dataForm),
                        url: url,
                        success: function (response){
                            if(response.respuesta){
                                messageResponse({
                                    message: response.mensaje,
                                    type: "success"
                                });
                                $("#modalRole").modal('hide');
                                setTimeout(function () {
                                    window.location.reload();
                                },2500)
                            }
                            else{
                                messageResponse({
                                    message: response.mensaje,
                                    type: "error"
                                });
                            }
                        },
                        beforeSend: function () {
                            block_general('body')
                        },
                        complete: function () {
                            unblock('body')
                        },
                        error: function (xmlHttpRequest, textStatus, errorThrow) {
                            console.error(errorThrow)
                        }
                    })
                },
            })

        })
        $(document).on('click','#btnCancelar',function(e){
            e.preventDefault()
            $("#modalRole").modal('hide');
        })
        $(document).on('click','.btnRecargar',function(e){
            e.preventDefault()
            window.location.reload();
        })
    }
     return {
        init: function() {
            inicio();
            acciones();
        }
    }
}();
document.addEventListener('DOMContentLoaded', function() {
    AdminRoleVistaJS.init();
});

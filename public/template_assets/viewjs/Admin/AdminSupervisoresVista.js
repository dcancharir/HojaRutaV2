let AdminSupervisoresVistaJS = function() {

    //
    // Setup module components
    //
    let arrayRolesUsuario=[];
    let roles;
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
                    roles=response.dataRoles;
                    let columns=columnasDatatable(data)
                    $.each(data,function(i,v){
                        let role_id;
                        if(v.roles.length>0){
                            role_id=v.roles[0].id
                        }
                        else{
                            role_id=0
                        }
                        arrayRolesUsuario.push({
                            supervisor_id:v.supervisor_id,
                            role_id:role_id,
                        })
                    })
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
                title: "Rol",
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
                        title: "Rol",
                        "className":'font-weight-semibold mb-0',
                        "render":function(value, type, oData, meta){
                            let span=`<select style="width:100%;" class="browser-default custom-select selectRol select${oData.supervisor_id}" data-id="${oData.supervisor_id}">`;
                            if(oData.roles.length>0){
                                let rol=oData.roles[0];
                                span+=`<option value="">Seleccione</option>`;
                                $.each(roles, function(index,val){
                                    if(val.name==rol.name){
                                        span+=`<option selected="selected" value="${val.id}">${val.name}</option>`;
                                    }else{
                                        span+=`<option value="${val.id}">${val.name}</option>`;
                                    }
                                })
                            }
                            else{
                                span+=`<option value="" selected>Seleccione</option>`;
                                $.each(roles,function(i,o){
                                    span+=`<option value="${o.id}">${o.name}</option>`;
                                })
                            }
                            span+=`</select>`;
                            return span
                        }
                    },

                ]
            }
        }
        return obj
    }
    let _actions=function(){
        $(document).on('change','.selectRol',function(e){
            e.preventDefault()
            let supervisor_id= $(this).data('id')
            let role_id=$(this).val()
            let dataForm={
                supervisor_id: supervisor_id,
                role_id: role_id
            }
            if(role_id!=''){
               let roleAnterior=arrayRolesUsuario.filter(x=>x.supervisor_id==supervisor_id)
               console.log(roleAnterior)
               let url= basePath + "AsignarRolSupervisorJson";
               messageConfirmation({
                    title: 'Confirmación',
                    content: '¿Seguro que desea editar el registro?',
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
                                console.log(response)
                                if(response.respuesta){
                                    messageResponse({
                                        message: response.mensaje,
                                        type: "success"
                                    });
                                    arrayRolesUsuario.map(function(dato){
                                        if(dato.supervisor_id == roleAnterior[0].supervisor_id){
                                          dato.role_id = role_id;
                                        }
                                        return dato;
                                    });
                                }
                                else{
                                    messageResponse({
                                        message: response.mensaje,
                                        type: "error"
                                    });
                                    $('.select'+supervisor_id).val(roleAnterior[0].role_id);
                                }
                            },
                            error: function (xmlHttpRequest, textStatus, errorThrow) {
                                console.log("errorrrrrrrr");
                                $('.select'+supervisor_id).val(roleAnterior[0].role_id);
                            }
                        });
                    },
                    callBackSCCerraromplete:function(){
                        $('.select'+supervisor_id).val(roleAnterior[0].role_id);
                    }
                })

            }
            else{
                messageResponse({
                    message: 'Debe seleccionar un rol',
                    type: "warning"
                });
            }
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
    AdminSupervisoresVistaJS.init();
});

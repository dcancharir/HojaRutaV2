let basePath=$("#PathProyecto").val()+"/";
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    error: function(xmlHttpRequest, textStatus, errorThrow) {
        messageResponse({
            message: errorThrow,
            type: "error"
        });
        console.log(errorThrow);
    },
    statusCode: {
        404: function() {
            messageResponse({
                message: "No Se encuentra la Direccion.(404)",
                type: "error"
            });
        },
        405: function() {
            messageResponse({
                message: "Metodo no Permitido.(GET,POST,PUT,DELETE)(405)",
                type: "error"
            });
        },
        500: function() {
            messageResponse({
                message: "Error Interno.(500)",
                type: "error"
            });
        }
    }
});

//seccion cookies
function setCookie(cname, cvalue, exdays) {
    let d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

function deleteCookie(cname) {
    setCookie(cname, "", -1);
}
function redirect(site) {
    window.location.href = basePath + site;
}
function block_general(block) {
    $(block).block({
        message: '<i class="icon-spinner4 spinner"></i>',
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.8,
            cursor: 'wait'
        },
        css: {
            width: 16,
            border: 0,
            padding: 0,
            backgroundColor: 'transparent'
        }
    });
}

function unblock(block) {
    $(block).unblock();
}
function messageResponse(obj) {
    let defaults = {
        message: null,
        type: null,
        timeOut: 2500,
        progressBar: true,
        closeWith: null,
        modal: false
    }

    let opciones = $.extend({}, defaults, obj);
    let theme = null;
    switch (opciones.type) {
        case 'success':
            theme = ' alert alert-success alert-styled-left p-0';
            break;
        case 'error':
            theme = ' alert alert-danger alert-styled-left p-0';
            break;
        case 'warning':
            theme = ' alert alert-warning alert-styled-left p-0';
            break;
        case 'info':
            theme = ' alert bg-info text-white alert-styled-left p-0';
            break;
        case 'alert':
            theme = {};
            break;
        default:
            theme = ' alert bg-info alert-styled-left p-0';
            break;
    }
    new Noty({
        theme: theme,
        text: opciones.message,
        type: opciones.type,
        progressBar: opciones.progressBar,
        timeout: opciones.timeOut,
        closeWith: opciones.closeWith,
        modal:opciones.modal
    }).show();
}
//////////////////////////////////////////////////////////////////////////////

$.fn.serializeFormJSON = function () {

    let o = {};
    let a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
}
function messageConfirmation(obj) {
    let defaults = {
        title: 'Confirmacion',
        content: '¿Seguro que desea proceder?',
        icon: 'fa fa-warning',
        callBackSAceptarComplete: null,
        callBackSCCerraromplete: null,
    };
    let opciones = $.extend({}, defaults, obj);
    $.confirm({
        title: opciones.title,
        theme: 'modern',
        icon: opciones.icon,
        content: opciones.content,
        buttons: {
            Aceptar: function () {
                if (opciones.callBackSAceptarComplete != null) {
                    opciones.callBackSAceptarComplete();
                }
            },
            Cerrar: function () {
                if (opciones.callBackSCCerraromplete != null) {
                    opciones.callBackSCCerraromplete();
                }
            },

        }
    });
}
$.extend($.fn.dataTable.defaults, {
    autoWidth: false,
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    language: {
        search: '<span>Buscar:</span> _INPUT_',
        searchPlaceholder: '...',
        lengthMenu: '<span>Mostrar:</span> _MENU_',
        paginate: {
            'first': 'First',
            'last': 'Last',
            'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
            'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
        },
        emptyTable: "No hay Data que Mostrar",
        infoEmpty: "Mostrando 0 al 0 de 0 Registros",
        infoFiltered: "(Filtrado de _MAX_ Registro(s))",
        info: "Mostrando _START_ al _END_ de _TOTAL_ Registros",
        loadingRecords: "Cargando...",
        processing: "Procesando...",
        zeroRecords: "No Se encontro Coincidencias"
    }
});

// Sidebar
let CURRENT_URL = window.location.href.split('#')[0].split('?')[0];
let SIDEBAR_MENU = $('.nav-sidebar');
SIDEBAR_MENU.find('a').filter(function () {
        return this.href == CURRENT_URL;
}).addClass('active').parent('li').parent('ul').parent('li').addClass('nav-item-expanded nav-item-open');











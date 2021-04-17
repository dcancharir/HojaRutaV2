let VentaVistaJS=function(){
    const tienda_id=$("#txt_tienda_id").val();
    let _inicio=function(){
        let url = basePath + "VentaListarJson";
        let dataForm={
            tienda_id:tienda_id,
        }
        $.ajax({
            url: url,
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(dataForm),
            beforeSend: function () {
                // $.LoadingOverlay("show");
            },
            complete: function () {
                // $.LoadingOverlay("hide");
            },
            success: function (response) {
                if(response.respuesta){
                    let columns=columnasDatatable(response.data)
                    objetodatatable = $(".datatable-venta").DataTable({
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
    let columnasDatatable=function (data){
        let obj=[
            {
                title: "Tienda"
            },
            {
                title: "CC"
            },
            {
                title: "Fecha",
            },
            {
                title: "Monto",
            },
            {
                title: "Moneda",
            },
        ]
        if(data){
            if(data.length>0){
                obj=[
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
                        data: "fecha",
                        title: "Fecha",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "monto",
                        title: "Monto",
                        "className":'font-weight-semibold mb-0'
                    },
                    {
                        data: "moneda",
                        title: "Moneda",
                        "className":'font-weight-semibold mb-0'
                    },

                ]
            }
        }
        return obj
    }
    let _componentes=function(){
        $(document).on("click", "#btnPdf", function() {
            window.open(basePath+ 'VentaExportarPdf/'+tienda_id);
        });
        $(document).on("click", "#btnExcel", function() {
            redirect("VentaExportarExcel/"+tienda_id);
        });
        $(document).on("click", ".btnRecargar", function() {
            refresh(true);
        });

         $(document).on("click", ".btnRegresar", function() {
             redirect("Tienda");
        });
    }
    return {
        init:function(){
            _inicio();
            _componentes();
        }
    }
}();
//Initialize module
document.addEventListener('DOMContentLoaded',function(){
    VentaVistaJS.init();
})

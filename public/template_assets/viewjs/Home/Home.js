let HomeJs=function(){
    let _inicio=function(){
        let url = basePath + "RutaDiariaJson";
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
                    if(response.data){
                        let data=response.data;
                        $("#totalTiendas").text(parseInt(data.tiendas_pendientes+data.tiendas_visitadas))
                        $("#totalPendientes").text(data.tiendas_pendientes)
                        $("#totalVisitadas").text(data.tiendas_visitadas)
                        let detalles=data.detalle_ruta;
                        let arrayTemplateDetalles=[];
                        let markers=[];
                        $.each(detalles,function(index,value){
                            //marcadores para maps
                            let marker=[
                                {
                                    lat:parseFloat(value.tienda.latitud),
                                    lng:parseFloat(value.tienda.longitud)
                                },
                                value.tienda.nombres
                            ];

                            markers.push(marker)
                            //llenado de template
                            let clase="";
                            if(value.tipo_detalle==1){
                                clase="bg-indigo";
                            }
                            else{
                                clase="bg-blue";
                            }
                            let td=``;
                            if(value.visita_id){
                                td=`<td align="center"><h6 class="text-success-600">Visitado</h6></td>`;
                            }
                            else{
                                td=`<td align="center">
                                    <a href="javascript:void(0);" class="btn btn-sm bg-danger-400 btnVisitar"
                                    data-latitud="${value.tienda.latitud}"
                                    data-longitud="${value.tienda.longitud}"
                                    data-detalle="${value.detalle_ruta_id}" data-nombres="${value.tienda.nombres}"
                                    data-rutaid="${value.ruta_id}"
                                    data-detallerutaid="${value.detalle_ruta_id}"
                                    >Visitar</a>
                                </td>`;
                            }
                            arrayTemplateDetalles.push(`
                                <tr>
                                    <td>
                                        ${value.orden}
                                    </td>
                                    <td><span class="font-weight-semibold mb-0">${value.tienda.nombres}</span></td>
                                    <td><span class="">${value.tienda.direccion}</td>
                                    <td><h6 class="text-success-600 badge ${clase}">${value.tipo_detalle==1?"Montos de Venta":"Frecuencia Semanal"}</h6></td>
                                    <td><span class="text-muted">${value.observacion}</span></td>
                                    ${td}
                                </tr>`);
                        })
                        //impresion de template
                        $("#tableDetalleRuta tbody").html("")
                        $("#tableDetalleRuta tbody").html(arrayTemplateDetalles.join(''))
                        //llenado de data para maps
                        let opciones={
                            center:{
                                latitud:parseFloat(detalles[0].tienda.latitud),
                                longitud:parseFloat(detalles[0].tienda.longitud)
                            },
                            markers:markers,
                        }
                        HomeJs.loadMap(opciones);
                    }
                   else{
                    messageResponse({
                        message: 'No se pudo cargar datos',
                        type: "error"
                    });
                   }
                }
                else {
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
    //   Line chart
    let _googleMapBasic = function(obj) {
        if (typeof google == 'undefined') {
            console.warn('Warning - Google Maps library is not loaded.');
            return;
        }
        $("#map").html('');
        let defaults={
            center:{
                latitud:-18.0322304,
                longitud: -70.2775296
            },
            markers:[
                [{ lat: -18.0322304, lng: -70.2775296 }, "Tacna"],
            ]
        };
        let opciones = $.extend({}, defaults, obj);
        let map_basic_element = document.getElementById('map');
        let mapOptions = {
            zoom: 18,
            center: new google.maps.LatLng(opciones.center.latitud, opciones.center.longitud)
        };
        let map = new google.maps.Map(map_basic_element, mapOptions);

        const tourStops = opciones.markers;
        const infoWindow = new google.maps.InfoWindow();
        tourStops.forEach(([position, title], i) => {
            const marker = new google.maps.Marker({
            position,
            map,
            title: `${i + 1}. ${title}`,
            label: `${i + 1}`,
            optimized: false,
            });
            marker.addListener("click", () => {
            infoWindow.close();
            infoWindow.setContent(marker.getTitle());
            infoWindow.open(marker.getMap(), marker);
            });
        });
        // Load map
        google.maps.event.addDomListener(window, 'load');
    };
    let getDistanceFromLatLonInMeters=function (lat1,lon1,lat2,lon2) {
    var R = 6378137; // Radius of the earth in meters
    var dLat = deg2rad(lat2-lat1);  // deg2rad below
    var dLon = deg2rad(lon2-lon1);
    var a =
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
        Math.sin(dLon/2) * Math.sin(dLon/2)
        ;
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var d = R * c; // Distance in meters
    return d.toFixed(3);
    }

    let deg2rad=function (deg) {
    return deg * (Math.PI/180)
    }
    let ListarPreguntasVisita=function (){
        let anexarPreguntaCajero=$("#responsabilidadCajero");
        let anexarPreguntaSupervisor=$("#responsabilidadSupervisor");
        anexarPreguntaCajero.html("");
        anexarPreguntaSupervisor.html("");
        let url = basePath + "ListarPreguntasyOpcionesJson";
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
                    let data=response.data;
                    if(data.preguntas.length>0&&data.opciones.length>0){
                        messageResponse({
                            message: response.mensaje,
                            type: "success"
                        });
                        let preguntas=data.preguntas;
                        let opciones=data.opciones;
                        let preguntasCajero=[];
                        let preguntasSupervisor=[];
                        let arraySelect=[];
                        let indicePreguntaCajero=1;
                        let indicePreguntaSupervisor=1;
                        $.each(opciones,function(index,opcion){
                            arraySelect.push(`<option value="${opcion.opcion_id}">${opcion.opcion}</option>'`);
                        });

                        $.each(preguntas,function(index,pregunta){
                            let template=`
                            <div class="form-group row"><label class="col-md-12 font-weight-semibold">${pregunta.tipo==1?indicePreguntaCajero:indicePreguntaSupervisor}.- ${pregunta.titulo}</label>
                                <div class="col-md-4">
                                    <select class="form-control" id="selectpregunta${pregunta.pregunta_id}" name="selectpregunta_${pregunta.pregunta_id}">
                                      ${arraySelect.join('')}
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="button" data-id="${pregunta.pregunta_id}" class="btn btn-primary btnObservacion" value="Agregar Observacion"/>
                                </div>
                                <hr>
                                <div class="col-md-12" style="display:none;padding-top:10px;" id="observacion${pregunta.pregunta_id}">
                                    <input type="text" placeholder="Observacion" class="form-control" name="observacion_${pregunta.pregunta_id}">
                                </div>
                            </div>`;
                            if(pregunta.tipo==1){
                                indicePreguntaCajero++;
                                preguntasCajero.push(template)
                            }else{
                                indicePreguntaSupervisor++;
                                preguntasSupervisor.push(template)
                            }
                        })
                        anexarPreguntaCajero.append(preguntasCajero.join(''));
                        anexarPreguntaSupervisor.append(preguntasSupervisor.join(''));
                        $("#modalVisita").modal("show");
                    }
                    else{
                        messageResponse({
                            message: 'No se pudieron listar las preguntas, contactese con su administrador',
                            type: "error"
                        });
                    }
                }
                else{{
                    messageResponse({
                        message: response.mensaje,
                        type: "danger"
                    });
                }}
            },
            error: function (xmlHttpRequest, textStatus, errorThrow) {
                console.log("errorrrrrrrr");
            }
        });
    }
    let _componentes=function(){
        $(document).on('click','.btnVisitar',function(){
            var ruta_id = $(this).data("rutaid");
            var detalleruta_id = $(this).data("detallerutaid");
            var latitud_tienda=$(this).data("latitud");
            var longitud_tienda=$(this).data("longitud");
            var nombreTienda=$(this).data("nombres");
            if (!navigator.geolocation){
                messageResponse({
                    message: "Tu Navegador no soporta Geolocalizacion",
                    type: "warning"
                });
            }
            else{
                function success(position) {

                    var latitud_persona  = position.coords.latitude;
                    var longitud_persona = position.coords.longitude;
                    var distancia=getDistanceFromLatLonInMeters(latitud_persona,longitud_persona,latitud_tienda,longitud_tienda);
                    if(distancia<distancia_aceptada){
                        ListarPreguntasVisita();
                        $("#nombreTienda").text(nombreTienda);
                        $("#ruta_id").val(ruta_id);
                        $("#nombre_tienda").val(nombreTienda);
                        $("#detalle_ruta_id").val(detalleruta_id);
                        $("#distancia").val(distancia);
                        //$("#modal_visita").modal("show");

                    }
                    else{

                        messageResponse({
                            message: "Usted se encuentra demasiado lejos de la Tienda, \n Distancia : "+distancia+" mts.",
                            type: "warning"
                        });
                    }

                  };

                  function error(error) {
                    var message="";
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            message="Permiso denegado por el Usuario.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message="Posición de ubicación no disponible.";
                            break;
                        case error.TIMEOUT:
                            message="Tiempo de espera superado.";
                            break;
                        case error.UNKNOWN_ERROR:
                            message="Error desconocido.";
                            break;
                    }
                    messageResponse({
                        message: message,
                        type: "warning"
                    });
                  };
                  navigator.geolocation.getCurrentPosition(success, error);
            }
        })
        $(document).on('click',".btnObservacion",function(){
            let idpregunta=$(this).data("id");
            let boton = $(this);
            $("input[name='observacion_"+idpregunta+"']").val("")
            $("#observacion"+idpregunta).toggle(500,
            function(){
                if($(this).is(':visible')){
                    boton.val("Cancelar");
                    boton.removeClass("btn-primary");
                    boton.addClass("btn-danger");
                }
                else{
                    boton.val("Agregar Observacion");
                    boton.addClass("btn-primary");
                    boton.removeClass("btn-danger");
                }
            });


            /** */
        });
        $(document).on('click','#btnCancelarVisita',function(){
            messageConfirmation({
                title: 'Confirmacion',
                content: 'Seguro que desa cancelar?, Se descartarán las preguntas contestadas',
                    callBackSAceptarComplete: function() {
                    $("#modalVisita").modal('hide');
                    }
                })
        });
        $(document).on('click','#btnVisitar',function(e){
            let url= basePath + "VisitaGuardarJson";
            let ruta_id=$("#ruta_id").val();
            let detalle_ruta_id=$("#detalle_ruta_id").val();
            e.preventDefault();
            let o = [];
            let a = $("#frmVisitaForm").serializeArray();
            let respuesta={};
            $.each(a, function (element,value) {
                let name=value.name;
                let res=name.split('_');
                let nombre=res[0]
                let id=res[1]
                if(nombre=='selectpregunta'){
                    respuesta.pregunta_id=id;
                    respuesta.opcion_id=value.value;
                }
                else{
                    respuesta.observacion=value.value;
                }
                if(respuesta.pregunta_id!=null&&respuesta.opcion_id!=null&&respuesta.observacion!=null){
                    o.push(respuesta)
                    respuesta={};
                }
            });
            let dataForm={
                ruta_id:ruta_id,
                detalle_ruta_id:detalle_ruta_id,
                listaRespuestas:o
            }
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
                        setTimeout(function () {
                            window.location.reload();
                        },2500)
                    }
                    else{
                        messageResponse({
                            message: response.mensaje,
                            type: "danger"
                        });
                   }
                }
            });
        })
    }

    return {
        init: function() {
            _inicio();
            _componentes();
        },
        loadMap: function(options) {
            _googleMapBasic(options);
        }
    }
}()
document.addEventListener('DOMContentLoaded',function(){
    HomeJs.init();
    setInterval(actualizar,1000); //iniciar temporizador
})
function actual() {
    let fecha=new Date(); //Actualizar fecha.
    let hora=fecha.getHours(); //hora actual
    let minuto=fecha.getMinutes(); //minuto actual
    let segundo=fecha.getSeconds(); //segundo actual
    if (hora<10) { //dos cifras para la hora
       hora="0"+hora;
       }
    if (minuto<10) { //dos cifras para el minuto
       minuto="0"+minuto;
       }
    if (segundo<10) { //dos cifras para el segundo
       segundo="0"+segundo;
       }
    //ver en el recuadro del reloj:
    let mireloj = hora+" : "+minuto+" : "+segundo;
            return mireloj;
}
function actualizar() { //función del temporizador
    let mihora=actual(); //recoger hora actual
    let mireloj=document.getElementById("reloj"); //buscar elemento reloj
    mireloj.innerHTML=mihora; //incluir hora en elemento
}

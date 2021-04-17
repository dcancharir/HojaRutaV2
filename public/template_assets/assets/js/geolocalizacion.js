if (!navigator.geolocation){
    messageResponse({
        message: "Tu Navegador no soporta Geolocalizacion",
        type: "warning"
    });
}
else{
    function success(position) {

        let lat  = position.coords.latitude;
        let lon = position.coords.longitude;
        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lon=' + lon + '&lat=' + lat).then(function(response) {
            return response.json();
        }).then(function(json) {
            document.getElementById('address').innerHTML = json.address.neighbourhood+", " + json.address.city;
        })
      };

      function error(error) {
        let message="";
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






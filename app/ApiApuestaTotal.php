<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiApuestaTotal extends Model
{
    //
    // public $api_key;
    public function ValidarLoginTokenApi($Usuario, $password)
    {
        $curl = curl_init();
        curl_setopt_array($curl,
            array(
                CURLOPT_URL => "127.0.0.1:8000/api/v1/auth/acceso",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS =>
                    "usuario=" . $Usuario . "&password=" . $password,
                CURLOPT_ENCODING => "",
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    "ContentType: application/json",
                ),
            ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }
    public function ListaTiendasSupervisorTokenApi($api_key)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://supervisores.api:8080/api/v1/tienda/supervisor",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: Bearer " . $api_key
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }
    public function listarDataRutasAPI(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://supervisores.api:8080/api/v1/dataHojaRuta",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }
}

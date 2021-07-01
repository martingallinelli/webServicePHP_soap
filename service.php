<?php

// clase servidor con los metodos 
require_once './classes/Server.php';

// url del servicio
const URL = 'http://localhost/Projects/SOAP/service.php';

// opciones configuracion del cliente
$options = array(
    'uri' => URL   // si usamos sin WSDL, debemos especificar a donde ira a buscar la peticion
);

//! nuevo objeto servidor
$soap_server = new SoapServer(null, $options);

//! clase que escucha las peticiones
// setClass(nombreClase)
$soap_server->setClass('Server');

//! procesar las peticiones
// handle() -> escuchar peticiones
$soap_server->handle();
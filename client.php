<?php

// clase functiones
require_once './classes/Functions.php';
// log
require_once './classes/Log.php';

// url del servicio
const URL = 'http://localhost/Projects/SOAP/service.php';

// opciones configuracion del cliente
$options = array(
    'location' => URL,  // uri del servidor
    'uri' => URL,       // target name space (si tiene)
    'trace' => true     // para capturar errores
);

try {
    //! nuevo objeto cliente
    // SoapClient(url_del_WSDL, array_opciones)
    $soap_client = new SoapClient(null, $options);  // null = no hay wsdl

	switch ($_POST['metodo'])
	{
		case 'obtenerCursos':
			//! ejecutar metodo listar todos los cursos
			// soapCall(nombreMetodo, nombreMetodo=>parametro)
			$response = $soap_client->__soapCall('obtenerCursos', ['obtenerCursos' => '']);
			break;
		case 'obtenerCursos':
			//! ejecutar metodo ver curso por id
			// soapCall(nombreMetodo, nombreMetodo=>parametro)
			$response = $soap_client->__soapCall('obtenerCurso', ['obtenerCurso' => $_POST['id']]);
			break;
		case 'actualizarCurso':
			// capturar datos recibidos
			$datos = [
				'id' => $_POST['id'],
				'nombre' => $_POST['nombre']
			];
	
			//! ejecutar metodo actualizar curso
			// soapCall(nombreMetodo, nombreMetodo=>parametro)
			$response = $soap_client->__soapCall('actualizarCurso', ['actualizarCurso' => $datos]);
			break;
		case 'eliminarCurso':
			//! ejecutar metodo ver curso por id
			// soapCall(nombreMetodo, nombreMetodo=>parametro)
			$response = $soap_client->__soapCall('eliminarCurso', ['eliminarCurso' => $_POST['id']]);
			break;
	}

	// analizar si hubo un error, sino analizar si hay parametro formato, y sino analizar en que formato se muestra (xml - json), y mostrar
	echo (isset($response['success']) 
			? $resp = json_encode($response)  
			// si no existe formato
			: (!isset($_POST['formato'])
				? $response
				// si existe formato
				: (($_POST['formato'] == 'xml') 
					? $resp = Functions::XML($response) 
					: $resp = json_encode($response)
				)
			)
		);

//! control de errores
} catch (Exception $e) {
	// guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
	// Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 400 | Method: PUT actualizarCurso');
	echo "Error: " . $e->getMessage();
	echo "Error: " . $e->getLine();
};
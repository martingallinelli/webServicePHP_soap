<?php

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

	// capturar el codigo del curso
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	
	if ($id == '') 
	{
		//! ejecutar metodo listar todos los cursos
		// soapCall(nombreMetodo, nombreMetodo=>parametro)
		$response = $soap_client->__soapCall('listarCursos', ['listarCursos' => '']);
	} else {
		//! ejecutar metodo ver curso por id
		// soapCall(nombreMetodo, nombreMetodo=>parametro)
		$response = $soap_client->__soapCall('getCurso', ['getCurso' => $id]);
	}

	// analizar formato de respuesta (XML - JSON)
	($_POST['formato'] == 'xml') ? $resp = XML($response) : $resp = json_encode($response);
	
	// mostrar resultado
	echo $resp;

//! control de errores
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
};

//! mostrar resultado en formato XML
function XML($response)
{
	//! construir un xml
	// simpleXMLElement() -> representa un elemento en un documento XML
	$xml = new simpleXMLElement('<cursos></cursos>');
	foreach ($response as $resp) {
		//! agregar hijos
		// addChild(nombreEtiqueta, [valor]) -> añade un elemento hijo al nodo XML
		// addAttribute() -> añade un atributo al elemento 
		$curso = $xml->addChild('curso');
		$curso->addChild('nombre', $resp['nombre']);
	}
	
	//! cambiar el formato de salida (content-type text/html a text/xml)
	header('content-type: text/xml');
	//! imprimir resultado
	// asXML() -> retorna un string XML
	return $xml->asXML();
}
<?php

class Functions
{
    //! mostrar resultado en formato XML
    public static function XML($response)
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
}

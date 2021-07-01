<?php 

class Answers
{
    /**
     * cod_200
     *
     * @param  mixed $valor
     * @return array
     */
    public static function cod_200($valor)
    {
        $response['success'] = '200';
        $response['result'] = $valor;
        return $response;
    }

    /**
     * cod_201
     *
     * @param  mixed $valor
     * @return array
     */
    public static function cod_201()
    {
        $response['success'] = '201';
        $response['result'] = 'Elemento guardado!';
        return $response;
    }
    
    /**
     * error_400
     *
     * @param  mixed $valor
     * @return array
     */
    public static function error_400($valor = 'Datos enviados incompletos o con formato incorrecto')
    {
        $response['success'] = '400';
        $response['result'] = $valor;
        return $response;
    }
    
    /**
     * error_401
     *
     * @return array
     */
    public static function error_401()
    {
        $response['success'] = '401';
        $response['result'] = 'Acceso no autorizado';
        return $response;
    }
    
    /**
     * error_404
     *
     * @return array
     */
    public static function error_404()
    {
        $response['success'] = '404';
        $response['result'] = 'Recurso no encontrado';
        return $response;
    }

    /**
     * error_405
     *
     * @return array
     */
    public static function error_405()
    {
        $response['success'] = '405';
        $response['result'] = 'Metodo no permitido';
        return $response;
    }
  
    /**
     * error_500
     *
     * @return array
     */
    public static function error_500()
    {
        $response['success'] = '500';
        $response['result'] = 'Error interno del servidor';
        return $response;
    }

    /**
     * error
     *
     * @param  mixed $valor
     * @return array
     */
    public static function error($codigo, $valor)
    {
        $response['success'] = $codigo;
        $response['result'] = $valor;
        return $response;
    }
}
<?php

// conexion base de datos
require_once './config/Conection.php';
// respuestas
require_once './classes/errors/Answers.php';
// log
require_once './classes/Log.php';

class Server
{
    // mensajes
    private static $mensajes = [
        '200' => 'Ok',
        '201' => 'Curso guardado',
        '400' => 'Datos enviados incompletos o con formato incorrecto',
        '401' => 'Acceso no autorizado',
        '404' => 'Curso no encontrado',
        '405' => 'Método no permitido',
        '500' => 'Error interno del servidor'
    ];

    public function __construct() {}

    //! listar todos los cursos    
    /**
     * obtenerCursos
     *
     * @return array
     */
    public function obtenerCursos() 
    {
        //! conectar la bd
        $conn = Conection::conectar();
        //! consulta sql
        $sql = "SELECT nombre FROM cursos";
        //! guardar la consulta en memoria para ser analizada 
        $stmt = $conn->prepare($sql);
        //! ejecutar consulta
        if ($stmt->execute()) {
            // traer el curso en un array asociativo
            $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
            Log::saveLog('a+', 'Se listaron todos los cursos correctamente! HTTP Status Code: 200 | Method: GET obtenerCursos');
            // devolver cursos
            return $cursos;
        } else {
            // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
            Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 500 | Method: GET obtenerCursos');
            // error 500 (interno del servidor)
            return Answers::mensaje('500', self::$mensajes['500']);
        }
    }

    //! listar un curso por id    
    /**
     * obtenerCurso
     *
     * @param  mixed $id
     * @return array
     */
    public function obtenerCurso($id) 
    {
        //* si el id esta vacio
        if($id == "")
        {
            // error 400 datos incompletos
            return Answers::mensaje('400', self::$mensajes['400']);
        //* si el id esta ok
        } else {
            //! conectar la bd
            $conn = Conection::conectar();
            //! consulta sql
            $sql = "SELECT nombre FROM cursos WHERE id = :id";
            //! guardar la consulta en memoria para ser analizada 
            $stmt = $conn->prepare($sql);
            //! bindear parametros
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            //! ejecutar consulta
            if ($stmt->execute()) {
                // traer el curso en un array asociativo
                $curso = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // si encontro el curso
                if ($curso)
                {
                    // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
                    Log::saveLog('a+', 'Se listo el curso ' . $id . '! HTTP Status Code: 200 | Method: GET obtenerCurso');
                    return $curso;
                // si no encontro el curso
                } else {
                    // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
                    Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 404 | Method: GET obtenerCurso');
                    // devolver 404 no encontrado
                    return Answers::mensaje('404', self::$mensajes['404']);
                }
            } else {
                // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
                Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 500 | Method: GET obtenerCursos');
                // error 500 (interno del servidor)
                return Answers::mensaje('500', self::$mensajes['500']);
            }
        }
    }
}
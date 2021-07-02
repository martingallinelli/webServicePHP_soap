<?php

// conexion base de datos
require_once './config/Conection.php';
// respuestas
require_once './classes/errors/Answers.php';

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
     * listarCursos
     *
     * @return array
     */
    public function listarCursos() 
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
        } else {
            // error 500 (interno del servidor)
            return Answers::mensaje('500', self::$mensajes['500']);
        }

        //! devolver cursos
        return $cursos;
    }

    //! listar un curso por id    
    /**
     * getCurso
     *
     * @param  mixed $id
     * @return array
     */
    public function getCurso($id) 
    {
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
            // devolver curso o 404 no encontrado
            return $curso ? $curso : Answers::mensaje('404', self::$mensajes['404']);
        } else {
            // error 500 (interno del servidor)
            return Answers::mensaje('500', self::$mensajes['500']);
        }
    }
}
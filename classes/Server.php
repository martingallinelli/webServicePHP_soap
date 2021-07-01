<?php

// conexion base de datos
require_once './config/Conection.php';
// respuestas
require_once './classes/errors/Answers.php';

class Server
{
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
            return Answers::error_500();
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
        // nuevo objeto respuesta
        $respuestas = new Answers;
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
            $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // error 500 (interno del servidor)
            return Answers::error_500();
        }

        //! devolver curso
        return $cursos;
    }
}
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

    //! capturar datos y crear curso    
    /**
     * capturarPost
     *
     * @param  mixed $array
     * @return array
     */
    public static function nuevoCurso($nombre)
    {
        //* si no existe alguno de los campos en los datos
        if($nombre == '')
        {
            // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
            Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 400 | Method: POST nuevoCurso');
            // error 400 datos incompletos
             return Answers::mensaje('400', self::$mensajes['400']);
        //* si existen los campos en los datos
        } else {
            //* guardar curso 
            $resp = self::insertarCurso($nombre);
            // si se guardo
            if($resp)
            {
                // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
                Log::saveLog('a+', 'Se guardo el nuevo curso! HTTP Status Code: 201 | Method: POST nuevoCurso');
                // devolver 201 elemento guardado
                return Answers::mensaje('201', self::$mensajes['201']);
            // si no se guardo
            } else {
                // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
                Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 500 | Method: POST nuevoCurso');
                // error 500 interno del servidor
                return Answers::mensaje('500', self::$mensajes['500']);
            }
        }
    } 

    //! insertar curso    
    /**
     * insertarCurso
     *
     * @param  mixed $nombre
     * @return bool
     */
    private static function insertarCurso($nombre)
    {
        //! conectar la bd
        $conn = Conection::conectar();
        //! consulta sql
        $sql = "INSERT INTO cursos (nombre) VALUES (:nombre)";
        //! guardar la consulta en memoria para ser analizada 
        $stmt = $conn->prepare($sql);
        //! bindear parametros
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        //! ejecutar consulta
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //! capturar datos y actualizar curso    
    /**
     * actualizarCurso
     *
     * @param  mixed $json
     * @return array
     */
    public function actualizarCurso($datos)
    {
        //* si el id recibido esta vacio
        if($datos['id'] == '')
        {
            // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
            Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 400 | Method: PUT actualizarCurso');
            // error 400 datos incompletos
            return Answers::mensaje('400', self::$mensajes['400']);
        } else {
            // si el nombre esta vacio o no existe
            if (!isset($datos['nombre']) || empty($datos['nombre']))
            {
                // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
                Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 400 | Method: PUT actualizarCurso');
                // error 400 datos incompletos
                return Answers::mensaje('400', self::$mensajes['400']);
            } else {
                //* actualizar curso 
                $resp = self::modificarCurso($datos['id'], $datos['nombre']);
                // si se actualizo
                if($resp)
                {
                    // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
                    Log::saveLog('a+', 'Se actualizo el curso ' . $datos['id'] . '! HTTP Status Code: 200 | Method: PUT actualizarCurso');
                    // devolver 200 curso actualizado
                    return Answers::mensaje('200', 'Curso actualizado');
                // si no se guardo
                } else {
                    // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
                    Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 404 | Method: PUT actualizarCurso');
                    // error 404 recurso no encontrado
                    return Answers::mensaje('404', self::$mensajes['404']);
                }
            }
        }
    }

    //! actualizar curso    
    /**
     * modificarCurso
     *
     * @param  mixed $id
     * @param  mixed $nombre
     * @return bool
     */
    private static function modificarCurso($id, $nombre)
    {
        //! conectar la bd
        $conn = Conection::conectar();
        //! consulta sql
        $sql = "UPDATE cursos SET nombre = :nombre WHERE id = :id";
        //! guardar la consulta en memoria para ser analizada 
        $stmt = $conn->prepare($sql);
        //! bindear parametros
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        //! ejecutar consulta
        if ($stmt->execute()) {
            // numero de filas afectadas
            return $stmt->rowCount();
        } else {
            // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
            Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 500 | Method: PUT actualizarCurso');
            // error 500 interno del servidor
            return Answers::mensaje('500', self::$mensajes['500']);
        }
    }

    //! capturar datos y eliminar post    
    /**
     * eliminarCurso
     *
     * @param  mixed $id
     * @return array
     */
    public function eliminarCurso($id)
    {
        //* si el id recibido esta vacio
        if($id == '')
        {
            // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
            Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 400 | Method: DELETE eliminarCurso');
            // error 400 datos incompletos
            return Answers::mensaje('400', self::$mensajes['400']);
        } else {
            //* eliminar curso 
            $resp = self::borrarCurso($id);
            // si se elimino
            if($resp)
            {
                // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
                Log::saveLog('a+', 'Se elimino el curso ' . $id . '! HTTP Status Code: 200 | Method: DELETE eliminarCurso');
                // devolver 200 curso eliminado
                return Answers::mensaje('200', 'Curso eliminado!');
            // si no se guardo
            } else {
                // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
                Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 404 | Method: DELETE eliminarCurso');
                // error 404 recurso no encontrado
                return Answers::mensaje('404', self::$mensajes['404']);
            }
        }
    }  

    //! eliminar post    
    /**
     * borrarCurso
     *
     * @param  mixed $id
     * @return bool
     */
    private static function borrarCurso($id)
    {
        //! conectar la bd
        $conn = Conection::conectar();
        //! consulta sql
        $sql = "DELETE FROM cursos WHERE id = :id";
        //! guardar la consulta en memoria para ser analizada 
        $stmt = $conn->prepare($sql);
        //! bindear parametros
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        //! ejecutar consulta
        if ($stmt->execute()) {
            // numero de filas afectadas
            return $stmt->rowCount();
        } else {
            // guardar log (a+, seguir escribiendo sin sobreescribir lo existente)
            Log::saveLog('a+', 'Ocurrio un error! HTTP Status Code: 500 | Method: DELETE eliminarCurso');
            // error 500 interno del servidor
            return Answers::mensaje('500', self::$mensajes['500']);
        }
    }
}
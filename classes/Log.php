<?php

// setear el uso horario de donde estamos
ini_set('date.timezone', 'America/Argentina/Buenos_Aires');

// archivo LOG
const LOG = './logs/log.txt';

class Log 
{
    //! guardar log = registro de acciones
    /**
     * saveLog
     *
     * @param  mixed $modo
     * @param  mixed $nombre_archivo
     * @param  mixed $mensaje
     * @return void
     */
    public static function saveLog($modo, $mensaje)
    {
        // fecha y hora
        $date = new DateTime();
        $datetime = $date->format("Y-m-d H:i:s");
        // abrir archivo
        $fp = fopen(LOG, $modo);
        // escribir en el archivo el mensaje
        // PHP_EOL -> fin de linea
        fwrite($fp, '[' . $datetime . '] ' . $mensaje . PHP_EOL);
        // cerrar archivo
        fclose($fp);
    }
}
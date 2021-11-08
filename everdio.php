<?php
\ini_set("display_errors", 1);

//register_shutdown_function("shutdown");

function shutdown() {
    $error = error_get_last();    
    if ($error) {
        throw new \ErrorException($error["message"], $error["type"], 0, $error["file"], $error["line"]);
    }
}


\set_error_handler(function($errno, $errstr, $errfile, $errline ){
    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
});

\spl_autoload_register(function ($name) {    
    include_once __DIR__ . \DIRECTORY_SEPARATOR . \strtolower(\dirname($reversed = \implode(\DIRECTORY_SEPARATOR, \explode("\\", $name)))) . \DIRECTORY_SEPARATOR . \basename($reversed . ".php");
});

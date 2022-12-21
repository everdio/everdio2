<?php
\ini_set("display_errors", 1);

\set_error_handler(function($errno, $errstr, $errfile, $errline ){
    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
});

register_shutdown_function(function () {
   
});

\spl_autoload_register(function ($name) {    
    include_once __DIR__ . \DIRECTORY_SEPARATOR . \strtolower(\dirname($reversed = \implode(\DIRECTORY_SEPARATOR, \explode("\\", $name)))) . \DIRECTORY_SEPARATOR . \basename(\sprintf("%s.php", $reversed));
});

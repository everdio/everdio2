<?php
/*
function exceptions_error_handler($severity, $message, $filename, $lineno) { 
    throw new ErrorException($message, 0, $severity, $filename, $lineno); 
}
set_error_handler('exceptions_error_handler');
 * 
 */
ini_set("display_errors",true);
spl_autoload_register(function ($name) {    
    include_once __DIR__ . DIRECTORY_SEPARATOR . strtolower(dirname($reversed = implode(DIRECTORY_SEPARATOR, explode("\\", $name)))) . DIRECTORY_SEPARATOR . basename($reversed . ".php");
});
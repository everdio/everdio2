<?php
\libxml_use_internal_errors(false);

\define("AUTOLOAD", __FILE__);

\set_error_handler(function($errno, $errstr){
    throw new \Error($errstr, $errno);
});

\spl_autoload_register(function ($name) {    
    include_once __DIR__ . \DIRECTORY_SEPARATOR . \strtolower(\dirname($reversed = \implode(\DIRECTORY_SEPARATOR, \explode("\\", $name)))) . \DIRECTORY_SEPARATOR . \basename($reversed . ".php");
});

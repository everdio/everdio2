<?php
spl_autoload_extensions(".php");
//register_shutdown_function("shutdown");
spl_autoload_register(function ($name) {    
    include_once __DIR__ . DIRECTORY_SEPARATOR . strtolower(dirname($reversed = implode(DIRECTORY_SEPARATOR, explode("\\", $name)))) . DIRECTORY_SEPARATOR . basename($reversed . ".php");
});
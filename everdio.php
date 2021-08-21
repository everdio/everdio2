<?php
ini_set("display_errors", true);
spl_autoload_register(function ($name) {    
    include_once __DIR__ . DIRECTORY_SEPARATOR . strtolower(dirname($reversed = implode(DIRECTORY_SEPARATOR, explode("\\", $name)))) . DIRECTORY_SEPARATOR . basename($reversed . ".php");
});
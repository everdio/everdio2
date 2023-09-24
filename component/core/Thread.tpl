<?php
include_once(__DIR__ . "/../everdio.php");

$controller = new {{extends}};
$controller->import({{parameters}});

try {
    echo $controller->dispatch($controller->arguments);
} catch (\Exception $ex) {
    echo \ucfirst($ex->getMessage()) . \PHP_EOL;
    if (isset($controller->request->{$controller->debug})) {
        echo $ex->getMessageAsString() . \PHP_EOL;
        if ($ex->getPrevious()) {
            echo $ex->getPrevious()->getMessage() . \PHP_EOL;
            echo $ex->getPrevious()->getTraceAsString();
        }        
    }
}

\unlink(__FILE__);

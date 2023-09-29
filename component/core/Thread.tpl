<?php
include_once(__DIR__ . "/../everdio.php");

$controller = new {{extends}};
$controller->import({{parameters}});

try {
    echo $controller->callback("{{callback}}");
} catch (\Exception | \ErrorException | \TypeError | \ParseError | \Error $ex) {
    echo \PHP_EOL . \ucfirst($ex->getMessage()) . \PHP_EOL;
    if (isset($controller->request->{$controller->debug})) {
        echo $ex->getTraceAsString() . \PHP_EOL;
        if ($ex->getPrevious()) {
            echo $ex->getPrevious()->getMessage() . \PHP_EOL;
            echo $ex->getPrevious()->getTraceAsString();
        }        
    }
}

\unlink(__FILE__);

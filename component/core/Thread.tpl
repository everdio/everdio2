<?php
include_once(__DIR__ . "/../everdio.php");

$controller = new {{extends}};
$controller->import({{parameters}});

try {
    echo $controller->callback("{{callback}}");
} catch (\UnexpectedValueException $ex) {
    echo \sprintf("Invalid parameter value %s in %s(%s)", $ex->getMessage(), $ex->getFile(), $ex->getLine()) . \PHP_EOL;
} catch (\InvalidArgumentException $ex) {
    echo \sprintf("Parameter %s required in %s(%s)", $ex->getMessage(), $ex->getFile(), $ex->getLine()) . \PHP_EOL;
} catch (\ErrorException | \TypeError | \ParseError | \Error $ex) {
    echo \sprintf("%s in %s (%s)", \ucfirst($ex->getMessage()), $ex->getFile(), $ex->getLine()) . \PHP_EOL;
}

\unlink(__FILE__);

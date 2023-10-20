<?php
include_once(__DIR__ . "/../everdio.php");

$controller = new {{extends}};
$controller->import({{parameters}});

try {
    echo $controller->callback("{{callback}}");
} catch (\RuntimeException | \LogicException $ex) {
    echo \sprintf("%s in %s(%s)", $ex->getMessage(), $ex->getFile(), $ex->getLine()) . \PHP_EOL;
} catch (\UnexpectedValueException $ex) {
    echo \sprintf("Invalid parameter value %s in %s(%s)", $ex->getMessage(), $ex->getFile(), $ex->getLine()) . \PHP_EOL;
} catch (\InvalidArgumentException $ex) {
    echo \sprintf("Parameter %s required in %s(%s)", $ex->getMessage(), $ex->getFile(), $ex->getLine()) . \PHP_EOL;
} catch (\Exception | \ErrorException | \TypeError | \ParseError | \Error $ex) {
    echo \sprintf("%s: %s in %s (%s)", \get_class($ex), \ucfirst($ex->getMessage()), $ex->getFile(), $ex->getLine()) . \PHP_EOL;
}

\unlink(__FILE__);

<?php
declare(ticks = 1);

function shutdown() {
    if (\is_file(__FILE__)) {
        \unlink(__FILE__);
    }
}

\register_shutdown_function("shutdown");

include_once("{{autoloader}}");

try {
    $controller = new {{class}};
    $controller->import({{parameters}});

    \pcntl_async_signals(true);
    \pcntl_signal(\SIGINT, array($controller, "terminate"));
    \pcntl_signal(\SIGTERM, array($controller, "terminate"));
    
    echo $controller->callback("{{callback}}");
} catch (\RuntimeException | \LogicException $ex) {
    $error = \sprintf("%s: %s in %s(%s)\n\n%s", \get_class($ex), $ex->getMessage(), $ex->getFile(), $ex->getLine(), $ex->getTraceAsString());
} catch (\UnexpectedValueException $ex) {
    $error = \sprintf("%s: Invalid parameter value %s in %s(%s)\n\n%s", \get_class($ex), $ex->getMessage(), $ex->getFile(), $ex->getLine(), $ex->getTraceAsString());
} catch (\InvalidArgumentException $ex) {
    $error = \sprintf("%s: Parameter %s required in %s(%s)\n\n%s", \get_class($ex), $ex->getMessage(), $ex->getFile(), $ex->getLine(), $ex->getTraceAsString());
} catch (\Exception | \ErrorException | \TypeError | \ParseError | \Error $ex) {
    $error = \sprintf("%s: %s in %s(%s)\n\n%s", \get_class($ex), \ucfirst($ex->getMessage()), $ex->getFile(), $ex->getLine(), $ex->getTraceAsString());
}

if (isset($error)) {
    if (isset($controller->request->{$controller->debug})) {
        (new \Component\Caller\File\Fopen(__DIR__ . \DIRECTORY_SEPARATOR . \basename(__FILE__, ".php") . ".err", "a"))->write($error);
        (new \Component\Caller\File\Fopen(__DIR__ . \DIRECTORY_SEPARATOR . \basename(__FILE__, ".php") . ".dbg", "a"))->write(\file_get_contents(__FILE__));
    }
    
    echo $error;
}

exit;
<?php
declare(ticks = 1);

\pcntl_alarm({{timeout}});
\pcntl_signal(\SIGALRM, "shutdown");
\pcntl_signal(\SIGINT, "shutdown");
\pcntl_signal(\SIGHUP, "shutdown");
\pcntl_signal(\SIGTERM, "shutdown");

function shutdown() {
    if (\is_file(__FILE__)) {
        \unlink(__FILE__);
    }
}

\register_shutdown_function("shutdown");

include_once("{{autoloader}}");

$controller = new {{class}};
$controller->import({{parameters}});

if (isset($controller->request->{$controller->debug})) {
    (new \Component\Caller\File\Fopen(__DIR__ . \DIRECTORY_SEPARATOR . \basename(__FILE__, ".php") . ".dbg", "a"))->write(\file_get_contents(__FILE__));
}

try {
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
    }
    
    echo $error;
}

exit;
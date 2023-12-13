<?php
declare(ticks = 1);

\pcntl_signal(\SIGINT, "shutdown");  
\pcntl_signal(\SIGHUP, "shutdown");  
\pcntl_signal(\SIGTERM, "shutdown"); 

function shutdown()  { 
    if (\is_file(__FILE__)) {
        \unlink(__FILE__);
    }
}

\register_shutdown_function("shutdown");

include_once("/home/evertdf/everdio2/everdio.php");

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

exit;
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
} catch (\Exception $ex) {
    echo \sprintf("%s: %s in %s(%s)\n\n%s", \get_class($ex), \ucfirst($ex->getMessage()), $ex->getFile(), $ex->getLine(), $ex->getTraceAsString());
}

exit;
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
    \pcntl_signal(\SIGINT, [$controller, "terminate"]);
    \pcntl_signal(\SIGTERM, [$controller, "terminate"]);
    
    echo $controller->callback("{{callback}}");
} catch (\Exception $ex) {
    echo \sprintf("%s: %s\n", \get_class($ex), \ucfirst($ex->getMessage()));
    if (isset($controller->debug) && isset($controller->request->{$controller->debug})) {
        echo \sprintf("\n%s\n", $ex->getTraceAsString());
        if ($ex->getPrevious()) {
            echo \sprintf("\n%s: %s\n\n%s\n", \get_class($ex->getPrevious()), \ucfirst($ex->getPrevious()->getMessage()), $ex->getPrevious()->getTraceAsString());
        }
    }
}

exit;
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
    \pcntl_signal(\SIGTERM, [$controller, "terminate"], false);
    \pcntl_signal(\SIGINT, [$controller, "terminate"], false);

    echo $controller->callback("{{callback}}");
} catch (\Exception | \Error $ex) {
    (new \Component\Caller\File\Fopen(__DIR__ . \DIRECTORY_SEPARATOR . \basename(__FILE__, ".php") . ".err", "a"))->write(\sprintf("%s: %s\n\n%s\n", \get_class($ex), \ucfirst($ex->getMessage()), $ex->getTraceAsString()));
}

exit;
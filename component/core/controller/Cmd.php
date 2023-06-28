<?php
namespace Component\Core\Controller {
    final class Cmd extends \Component\Core\Controller {        
        final public function execute(string $path) {
            if (\strtolower(\PHP_OS) === "linux") {
                \exec(\sprintf("./everdio %s > /dev/null &", $path));
            }
        }
    }
}
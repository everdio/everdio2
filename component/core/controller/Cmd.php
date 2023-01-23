<?php
namespace Component\Core\Controller {
    final class Cmd extends \Component\Core\Controller {        
        final public function exec(string $execute) {
            \exec(sprintf("./everdio %s > /dev/null &", $execute));
        }
    }
}
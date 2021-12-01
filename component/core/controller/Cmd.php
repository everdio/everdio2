<?php
namespace Component\Core\Controller {
    final class Cmd extends \Component\Core\Controller {        
        final public function run(string $path, array $parameters = []) {
            $exec = \sprintf("%s%s %s > /dev/null &", $path, (isset($this->arguments) ? \sprintf("--%s", \implode(" --", $this->arguments)) : false), \http_build_query($this->request->restore($parameters), false, " "));
            if (isset($this->request->dry)) {
                echo $exec . PHP_EOL;
                ob_flush();
            } else {
                \exec($exec);            
            }
        }
    }
}
<?php
namespace Component\Core\Controller\Model\Cli {
    final class Cmd extends \Component\Core\Controller\Model\Cli {        
        public function run(string $path, array $parameters = []) {
            $exec = \sprintf("%s %s %s > /dev/null &", $path, (isset($this->arguments) ? \sprintf("--%s", \implode(" --", $this->arguments)) : false), \http_build_query($this->request->restore($parameters), false, " "));
            if (isset($this->request->dry)) {
                return (string) $exec . \PHP_EOL;
            } else {
                \exec($exec);
            }
        }
    }
}
<?php
namespace Component\Core\Controller {
    final class Pm extends \Component\Core\Controller {   
        public function execute(string $path) {
            \exec(\sprintf("%s %s %s > /dev/null &", $path, (isset($this->arguments) ? \sprintf("--%s", \implode(" --", $this->arguments)) : false), \http_build_query($this->request->restore($parameters), false, " ")));            
        }
    }
}
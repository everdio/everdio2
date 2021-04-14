<?php
namespace Component\Core\Controller\Model {
    final class Cmd extends \Component\Core\Controller\Model {        
        final public function process($route) {
            exec(sprintf("%s --%s %s > /dev/null &", $route, (isset($this->arguments) ? implode(DIRECTORY_SEPARATOR, $this->arguments) : false), $this->request->querystring($this->request->diff())));
        }
    }
}
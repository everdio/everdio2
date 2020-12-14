<?php
namespace Components\Core\Controller\Model {
    final class Cmd extends \Components\Core\Controller\Model {
        public function __construct(\Components\Parser $parser) {
            parent::__construct([], $parser);
        }
        
        final public function display($route) {
            exec(sprintf("%s --%s %s > /dev/null &", $route, (isset($this->arguments) ? implode(DIRECTORY_SEPARATOR, $this->arguments) : false), $this->request->export()));
        }
    }
}
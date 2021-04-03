<?php
namespace Component\Core\Controller\Model {
    final class Cmd extends \Component\Core\Controller\Model {
        public function __construct(\Component\Parser $parser) {
            parent::__construct([], $parser);
        }
        
        final public function display($route) {
            exec(sprintf("%s --%s %s > /dev/null &", $route, (isset($this->arguments) ? implode(DIRECTORY_SEPARATOR, $this->arguments) : false), $this->request->querystring($this->requset->diff())));
        }
    }
}
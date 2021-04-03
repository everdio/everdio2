<?php
namespace Component\Core\Parameters\Controller\Model {
    final class Cmd extends \Component\Core\Parameters\Controller\Model {
        public function __construct(\Component\Parser $parser) {
            parent::__construct($parser);
        }
        
        final public function execute(string $path) {
            exec(sprintf("%s --%s %s > /dev/null &", $path, (sizeof($this->arguments) ? implode(DIRECTORY_SEPARATOR, $this->arguments) : false), $this->request->querystring($this->requset->diff())));
        }
    }
}
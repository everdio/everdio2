<?php
namespace Components\Core\Controller\Model {
    use \Components\Validation, \Components\Validator;
    final class Cmd extends \Components\Core\Controller\Model {
        public function __construct(\Components\Parser $parser) {
            parent::__construct([
                "root" => new Validation(realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR), [new Validator\IsString\IsDir])
            ], $parser);
        }
        
        final public function display($route) {
            exec(sprintf("%s/everdio %s %s %s > /dev/null &", $this->root, $route, (isset($this->arguments) ? implode(DIRECTORY_SEPARATOR, $this->arguments) : false), (isset($this->request) ? $this->sanitize(http_build_query($this->request, false, " ")) : false)));
        }
    }
}
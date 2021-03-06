<?php
namespace Components\Core\Adapter {
    use \Components\Validation;
    use \Components\Validator;    
    abstract class Model extends \Components\Core\Adapter {
        use \Components\Core\Model;
        public function __construct(array $parameters = []) {
            parent::__construct([
                "model" => new Validation(__DIR__ . DIRECTORY_SEPARATOR . "Model.tpl", [new Validator\IsString\IsFile]),
                "namespace" => new Validation(false, [new Validator\IsString]),
                "class" => new Validation(false, [new Validator\IsString]),
                "use" => new Validation(false, [new Validator\IsString])
            ] + $parameters);
        }                
        
        abstract public function setup();
    }
}
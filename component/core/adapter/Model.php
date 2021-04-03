<?php
namespace Component\Core\Adapter {
    use \Component\Validation;
    use \Component\Validator;    
    abstract class Model extends \Component\Core\Adapter {
        use \Component\Core\Model;
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
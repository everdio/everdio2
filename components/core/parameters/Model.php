<?php
namespace Components\Core\Parameters {
    use \Components\Validation;
    use \Components\Validator;    
    class Model extends \Components\Core\Parameters {
        use \Components\Core\Model;
        public function __construct(array $parameters = []) {
            parent::__construct([
                "model" => new Validation(__DIR__ . DIRECTORY_SEPARATOR . "Model.tpl", [new Validator\IsString\IsFile]),
                "namespace" => new Validation(false, [new Validator\IsString]),
                "class" => new Validation(false, [new Validator\IsString]),
                "use" => new Validation(false, [new Validator\IsString])
            ] + $parameters);
        }                
    }
}
<?php
namespace Component\Core\Parameters {
    use \Component\Validation;
    use \Component\Validator;    
    class Model extends \Component\Core\Parameters {
        use \Component\Core\Model;
        public function __construct(array $parameters = []) {
            parent::__construct([
                "model" => new Validation(false, [new Validator\IsString\IsFile]),
                "namespace" => new Validation(false, [new Validator\IsString]),
                "class" => new Validation(false, [new Validator\IsString]),
                "use" => new Validation(false, [new Validator\IsString]),
                "mapper" => new Validation(false, [new Validator\IsString])
            ] + $parameters);
            $this->model = __DIR__ . DIRECTORY_SEPARATOR . "Model.tpl";
        }                
    }
}
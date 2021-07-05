<?php
namespace Component\Core\Parameters {
    use \Component\Validation;
    use \Component\Validator;    
    class Model extends \Component\Core\Parameters {
        use \Component\Core\Model;
        public function __construct(private array $_parameters = []) {
            parent::__construct(_parameters: [
                "model" => new Validation(false, [new Validator\IsString\IsFile]),
                "namespace" => new Validation(false, [new Validator\IsString]),
                "class" => new Validation(false, [new Validator\IsString]),
                "use" => new Validation(false, [new Validator\IsString]),
                "mapper" => new Validation(false, [new Validator\IsString])
            ] + $_parameters);
            $this->model = __DIR__ . DIRECTORY_SEPARATOR . "Model.tpl";
        }                
    }
}
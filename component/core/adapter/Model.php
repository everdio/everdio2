<?php
namespace Component\Core\Adapter {
    use \Component\Validation, \Component\Validator;    
    abstract class Model extends \Component\Core\Adapter {
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
        
        abstract public function setup() : void;
    }
}
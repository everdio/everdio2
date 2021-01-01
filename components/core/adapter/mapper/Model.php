<?php
namespace Components\Core\Adapter\Mapper {
    use \Components\Validation;
    use \Components\Validator;    
    abstract class Model extends \Components\Core\Adapter\Model {
        public function __construct(array $parameters = []) {
            parent::__construct([
                "label" => new Validation(false, [new Validator\IsString]),                
                "primary" => new Validation(false, array(new Validator\IsArray)),
                "keys" => new Validation(false, array(new Validator\IsArray)),
                "parents" => new Validation(false, array(new Validator\IsArray)),                
                "mapping" => new Validation(false, array(new Validator\IsArray))                               
            ] + $parameters);
            
            $this->model = __DIR__ . DIRECTORY_SEPARATOR . "Model.tpl";
        }   
    }
}
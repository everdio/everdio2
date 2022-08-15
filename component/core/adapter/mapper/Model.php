<?php
namespace Component\Core\Adapter\Mapper {
    use \Component\Validation, \Component\Validator;
    abstract class Model extends \Component\Core\Adapter\Model {
        public function __construct(array $_parameters = []) {
            parent::__construct([
                "label" => new Validation(false, [new Validator\IsString]),                
                "primary" => new Validation(false, array(new Validator\IsArray)),
                "parents" => new Validation(false, array(new Validator\IsArray)),                
                "mapping" => new Validation(false, array(new Validator\IsArray))
            ] + $_parameters);
            
            $this->model = __DIR__ . \DIRECTORY_SEPARATOR . "Model.tpl";
        }   
    }
}
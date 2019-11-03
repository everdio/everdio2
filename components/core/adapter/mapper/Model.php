<?php
namespace Components\Core\Adapter\Mapper {
    use \Components\Validation;
    use \Components\Validator;
    abstract class Model extends \Components\Core\Adapter\Model {
        public function __construct($key) {
            parent::__construct($key);
            $this->add("mapping", new Validation(false, [new Validator\IsArray]));
        }
        
        abstract public function setup();        
    }
}
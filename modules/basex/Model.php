<?php
namespace Modules\BaseX {
    use \Components\Validation;
    use \Components\Validator;     
    class Model extends \Components\Core\Adapter\Model {
        public function __construct($key) {
            parent::__construct($key);
            $this->add("username", new Validation(false, [new Validator\IsString]));
            $this->add("password", new Validation(false, [new Validator\IsString]));
            $this->add("host", new Validation(false, [new Validator\IsString\IsUrl]));
            $this->add("query", new Validation(false, [new Validator\IsString]));            
            $this->extends = "\Modules\BaseX";            
        }
    }
}
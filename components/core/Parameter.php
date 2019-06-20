<?php
namespace Components\Core {
    use \Components\Validation;
    use \Components\Validator;
    abstract class Parameter extends \Components\Core {
        public function __construct() {
            $this->add("parameter", new Validation(false, array(new Validator\IsString)));
            $this->add("field", new Validation(false, array(new Validator\IsString)));
            $this->add("default", new Validation(false, array(new Validator\IsString, new Validator\IsInteger, new Validator\IsEmpty)));
            $this->add("length", new Validation(false, array(new Validator\IsNumeric)));
            $this->add("mandatory", new Validation(false, array(new Validator\IsBool)));
        }

        public function getValidation(array $validators = [], string $validate = NULL) : Validation {
            if (isset($this->length)) {
                $validators[] = new Validator\Len\Smaller($this->length);
            }
            
            if ($this->mandatory === false) {
                $validators[] = new Validator\IsEmpty;
                $validate = Validation::NORMAL;
            } elseif (!$validate && sizeof($validators) > 0) {
                $validate = Validation::STRICT;
            } else {
                $validate = Validation::NORMAL;
            }
                        
            return new Validation($this->default, $validators, $validate);
        }
        
        abstract public function getValidators(array $validators = []) : array;
    }
}
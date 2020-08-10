<?php
namespace Components\Validation {
    use \Components\Validation;
    use \Components\Validator;
    class Parameter extends \Components\Validation {
        private $_parameter, $_mandatory, $_default;
        private $_length = 0;
        public function __construct(string $parameter, $value = false, bool $default = NULL, bool $mandatory = true, int $length = NULL, array $options = []) {
            $this->_parameter = $parameter;
            $this->_length = $length;
            $this->_mandatory = $mandatory;
            $this->_default = $default;
            
            parent::__construct($value, [new Validator\IsArray, 
                                         new Validator\IsFloat, 
                                         new Validator\IsDouble, 
                                         new Validator\IsResource,
                                         new Validator\IsObject, 
                                         new Validator\IsNumeric,
                                         new Validator\IsString, 
                                         new Validator\IsArray\Intersect($options),
                                         new Validator\IsString\InArray($options),     
                                         new Validator\IsString\IsPath,
                                         new Validator\IsString\IsUrl,
                                         new Validator\IsString\IsDatetime,                                          
                                         new Validator\IsString\IsDatetime\IsDate]);
        }
        
        final public function __toString() : string {
            return (string) $this->_parameter;
        }
        
        final public function getValidators(array $validators = []) : array {
            if ($this->isValid()) {
                foreach ($this->validated() as $validator) {
                    $validators[] = $validator;
                }                
            } else {
                $validators[] = new Validator\IsString;
                $validators[] = new Validator\IsNumeric;
            }
            
            if ($this->_mandatory === false) {
                $validators[] = new Validator\IsEmpty;
            }
            
            if ($this->_mandatory && $this->_length) {
                $validators[] = new Validator\Len\Smaller($this->_length);
            }            
            
            return (array) $validators;
        }        

        final public function getValidation(array $validators = [], string $validate = NULL) : Validation {
            if ($this->_mandatory === false) {
                $validate = self::NORMAL;
            } elseif (!$validate && $this->_length && $this->_mandatory) {
                $validate = self::STRICT;
            } else {
                $validate = self::NORMAL;
            }
            
            return new Validation(($this->_default ? $this->value : false), array_unique($validators), $validate);            
        }
    }
}
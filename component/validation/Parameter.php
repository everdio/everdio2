<?php
namespace Component\Validation {
    use \Component\Validation, \Component\Validator;
    class Parameter extends \Component\Validation {
        private $_parameter, $_mandatory, $_default;
        private $_length = 0;
        public function __construct(string $parameter, $value = false, bool $default = false, bool $mandatory = true, int $length = NULL, array $options = []) {
            $this->_parameter = $parameter;
            $this->_length = $length;
            $this->_mandatory = $mandatory;
            $this->_default = $default;
            parent::__construct($value, [new Validator\IsArray, 
                                         new Validator\IsFloat, 
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
                $validators[] = new Validator\IsDefault;
            }
            
            if ($this->_mandatory === false) {
                $validators[] = new Validator\IsEmpty;
            }
            
            //if ($this->_mandatory && $this->_length) {
            if ($this->_length) {
                $validators[] = new Validator\Len\Smaller($this->_length);
            }            
            
            return (array) $validators;
        }        

        final public function getValidation(array $validators = [], string $validate = self::NORMAL) : Validation {
            if ($this->_length && $this->_mandatory) {
                $validate = self::STRICT;
            }
            
            return new Validation(($this->_default ? $this->value : false), array_unique($validators), $validate);            
        }
    }
}
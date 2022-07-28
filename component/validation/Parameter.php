<?php
namespace Component\Validation {
    use \Component\Validation, \Component\Validator;
    class Parameter extends \Component\Validation {
        public function __construct($value = false, private bool $_default = false, private bool $_mandatory = true, private $_length = NULL, array $options = []) {
            parent::__construct($value, [new Validator\IsArray, 
                                         new Validator\IsFloat, 
                                         new Validator\IsResource,
                                         new Validator\IsObject, 
                                         new Validator\IsNumeric,
                                         new Validator\IsString, 
                                         new Validator\IsBool, 
                                         new Validator\IsArray\Intersect($options),
                                         new Validator\IsString\InArray($options),     
                                         new Validator\IsString\IsPath,
                                         new Validator\IsString\IsUrl,
                                         new Validator\IsString\IsDatetime,                                          
                                         new Validator\IsString\IsDatetime\IsDate]);
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
            
            if ($this->_length) {
                $validators[] = new Validator\Len\Smaller($this->_length);
            }            
            
            return (array) $validators;
        }        

        final public function getValidation(array $validators = [], string $validate = self::NORMAL) : Validation {
            if ($this->_length && $this->_mandatory) {
                $validate = self::STRICT;
            }
            
            return new Validation(($this->_default ? $this->value : false), \array_unique($validators), $validate);            
        }
    }
}
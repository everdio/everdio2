<?php
namespace Components\Validation {
    use \Components\Validation;
    use \Components\Validator;
    class Parameter extends \Components\Validation {
        private $_parameter = false;
        private $_length = 0;
        private $_mandatory = false;
        private $_default = false;
        public function __construct(string $parameter, $value = false, bool $default = false, bool $mandatory = false, int $length = 0, array $options = []) {
            $this->_parameter = $this->labelize($parameter);
            $this->_length = $length;
            $this->_mandatory = $mandatory;
            $this->_default = $default;
            parent::__construct($value, [new Validator\IsArray, 
                                         new Validator\IsFloat, 
                                         new Validator\IsDouble, 
                                         new Validator\IsResource,
                                         new Validator\IsObject, 
                                         new Validator\IsNumeric,
                                         new Validator\IsNumeric\Decimal, 
                                         new Validator\IsString, 
                                         new Validator\IsArray\Intersect($options),
                                         new Validator\IsString\InArray($options),     
                                         new Validator\IsString\IsPath,
                                         new Validator\IsString\IsDir,  
                                         new Validator\IsString\IsFile,
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
                $validators[] = new Validator\IsEmpty;
            }
            
            return (array) $validators;
        }        

        final public function getValidation(array $validators = [], string $validate = NULL) : Validation {
            if ($this->_default && $this->_length) {
                $validators[] = new Validator\Len\Smaller($this->_length);
            }
            
            if ($this->_mandatory === false) {
                $validators[] = new Validator\IsNull;
                $validate = self::NORMAL;
            } elseif (!$validate && sizeof($validators) > 0) {
                $validate = self::STRICT;
            } else {
                $validate = self::NORMAL;
            }
                        
            return new Validation(($this->_default ? $this->get() : false), array_unique($validators), $validate);            
        }
    }
}
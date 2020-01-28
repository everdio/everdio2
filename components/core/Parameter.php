<?php
namespace Components\Core {
    use \Components\Validation;
    use \Components\Validator;
    class Parameter extends \Components\Core {
        public function __construct(string $parameter) {
            $this->add("parameter", new Validation($this->labelize($parameter), array(new Validator\IsString)));
            $this->add("default", new Validation(false, array(new Validator\IsArray, new Validator\IsString, new Validator\IsInteger)));
            $this->add("length", new Validation(false, array(new Validator\IsNumeric)));
            $this->add("mandatory", new Validation(false, array(new Validator\IsBool)));
            $this->add("sample", new Validation(false, [new Validator\IsArray, 
                                                              new Validator\IsFloat, 
                                                              new Validator\IsResource,
                                                              new Validator\IsObject, 
                                                              new Validator\IsNumeric,
                                                              new Validator\IsNumeric\Decimal, 
                                                              new Validator\IsString, 
                                                              new Validator\IsString\IsPath,
                                                              new Validator\IsString\IsDir,  
                                                              new Validator\IsString\IsFile,
                                                              new Validator\IsString\IsUrl,
                                                              new Validator\IsString\IsDatetime, 
                                                              new Validator\IsString\IsDatetime\IsDate,                
                                                              new Validator\IsInteger]));
            //$this->add("options", new Validation(false, [new Validator\IsArray]));
        }
        
        public function getValidators(array $validators = []) : array {
            if (isset($this->sample)) {
                if ($this("sample")->isValid()) {
                    foreach ($this("sample")->validated() as $validator) {
                        $validators[] = $validator;
                    }
                } else {
                    $validators[] = new Validator\IsEmpty;
                }
            } else {
                $validators[] = new Validator\IsEmpty;
            }
            return (array) $validators;
        }        
        
        final public function getValidation(array $validators = [], string $validate = NULL) : Validation {
            if (isset($this->length)) {
                $validators[] = new Validator\Len\Smaller($this->length);
            }
            
            if ($this->mandatory === false) {
                $validators[] = new Validator\IsNull;
                $validate = Validation::NORMAL;
            } elseif (!$validate && sizeof($validators) > 0) {
                $validate = Validation::STRICT;
            } else {
                $validate = Validation::NORMAL;
            }
                        
            return new Validation((isset($this->default) ? $this->default : false), array_unique($validators), $validate);            
            
        }
    }
}
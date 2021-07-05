<?php
namespace Component\Core {
    use \Component\Validation, \Component\Validator;
    abstract class Caller extends \Component\Core {
        public function __construct($caller) {
            parent::__construct(_parameters: [
                "caller" => new Validation($caller, [new Validator\IsString]),
                "resource" => new Validation(false, [new Validator\IsResource, new Validator\IsObject])
            ]);
        }
        
        final public function __toString() : string {
            return (string) $this->caller;
        }
        
        final public function __call($name, $arguments) {
            if (\function_exists(sprintf("%s_%s", $this->caller, $name))) {
                if (isset($this->resource)) {
                    \array_unshift($arguments, $this->resource); 
                }
                return \call_user_func_array(sprintf("%s_%s", $this->caller, $name), $arguments);            
            }
            
            throw new \BadFunctionCallException(sprintf("unknown function call %s_%s(%s)", $this->caller, $name, $this->dehydrate($arguments)));
        }       
    }
}


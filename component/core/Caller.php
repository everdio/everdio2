<?php
namespace Component\Core {
    use \Component\Validation, \Component\Validator;
    abstract class Caller extends \Component\Core {
        public function __construct($caller) {
            parent::__construct([
                "caller" => new Validation($caller, [new Validator\IsString]),
                "resource" => new Validation(false, [new Validator\IsResource, new Validator\IsObject])
            ]);
        }
        
        final public function __toString() : string {
            return (string) $this->caller;
        }
        
        final public function __call($name, $arguments) {
            if (\function_exists($this->caller . $name)) {
                if (isset($this->resource)) {
                    \array_unshift($arguments, $this->resource); 
                }
                return \call_user_func_array($this->caller . $name, $arguments);            
            }
            
            throw new \BadFunctionCallException(sprintf("unknown function call %s (%s)", $this->caller . $name, $this->dehydrate($arguments)));
        }       
    }
}


<?php
namespace Components\Core {
    use \Components\Validation;
    use \Components\Validator;            
    abstract class Caller extends \Components\Core {
        public function __construct($caller) {
            $this->add("caller", new Validation($caller, [new Validator\IsString]));
            $this->add("resource", new Validation(false, [new Validator\IsResource]));
        }
        
        final public function __toString() : string {
            return (string) $this->caller;
        }
        
        abstract public function execute() : string;
        
        final public function __call($name, $arguments) {
            if (function_exists(sprintf("%s_%s", $this->caller, $name))) {
                if (isset($this->resource)) {
                    array_unshift($arguments, $this->resource); 
                }
                return call_user_func_array(sprintf("%s_%s", $this->caller, $name), $arguments);            
            }
            
            throw new Event(sprintf("unknown function call %s_%s(%s)", $this->caller, $name, $this->dehydrate($arguments)));
        }       
    }
}


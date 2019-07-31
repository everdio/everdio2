<?php
namespace Components\Core {
    use \Components\Validation;
    use \Components\Validator;    
    abstract class Mapping extends \Components\Core {
        public function __construct(\Components\Core\Mapping\Instance $instance) {
            $this->add("instance", new Validation($instance, [new Validator\IsObject\Of("\Components\Core\Mapping\Instance")]));            
        }
        
        public function hasField(string $field) : bool {
            return (bool) isset($this->mapping) &&  array_key_exists($field, $this->mapping);
        }

        public function getField(string $parameter) : string {
            if (isset($this->mapping) && $this->exists($parameter)) {
                return (string) array_search($parameter, $this->mapping);
            }
            
            throw new Event(sprintf("unknown parameter %s", $parameter));
        }

        public function getParameter(string $field) : string {
            if ($this->hasField($field)) {
                return (string) $this->mapping[$field];
            }
            
            throw new Event(sprintf("unknown field %s", $field));
        }       
        
        public function hasMapping() : bool{
            return (bool) (isset($this->mapping) && in_array(true, $this->validate($this->mapping)));
        }
        
        public function __call($name, $arguments) {
            if (!method_exists($this, $name) && method_exists($this->instance, $name)) {
                return call_user_func_array(array($this->instance, $name), $arguments);            
            } elseif (!method_exists($this, $name)) {
                throw new Event(sprintf("unknown function call %s(%s)", $name, $this->dehydrate($arguments)));
            }
        }
    }
}
<?php
namespace Components\Core {
    use \Components\Validation;
    use \Components\Validator;    
    abstract class Adapter extends \Components\Core {
        static protected $instances = [];
        public function __construct($key) {            
            $this->add("key", new Validation($key, [new Validator\IsString, new Validator\IsInteger]));            
            $this->add("instance", new Validation($this->_initialize(), [new Validator\IsObject]));            
        }
        
        final private function _initialize() {
            if (array_key_exists($this->key, self::$instances)) {
                return (object) self::$instances[$this->key];
            }
        }
        
        final public function __call($name, $arguments) {
            if (!method_exists($this, $name)) {
                return call_user_func_array(array($this->instance, $name), $arguments);            
            }
        }       
    }
}
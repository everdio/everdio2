<?php
namespace Components\Core {
    abstract class Adapter extends \Components\Core {
        static protected $instances = [];
        
        abstract protected function initialise();

        final public function uses(string $trait) : bool {
            return (bool) in_array($trait, class_uses($this));
        }
        
        final public function __call($name, $arguments) {
            if (!method_exists($this, $name)) {
                return call_user_func_array(array($this->initialise(), $name), $arguments);            
            }
        }
    }
}
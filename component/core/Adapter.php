<?php
namespace Component\Core {
    abstract class Adapter extends \Component\Core {
        static protected $adapters = [];        

        abstract protected function initialize();

        final public function uses(string $trait) : bool {
            return (bool) in_array($trait, class_uses($this));
        }
        
        final public function __call($name, $arguments) {
            if (!method_exists($this, $name)) {
                return call_user_func_array(array($this->initialize(), $name), $arguments);            
            }
        }
    }
}
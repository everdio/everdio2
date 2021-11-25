<?php
namespace Component\Core {
    abstract class Adapter extends \Component\Core {
        static public $adapters = [];        

        abstract protected function initialize();

        final public function uses(string $trait) : bool {
            return (bool) \in_array($trait, \class_uses($this));
        }
                        
        public function __call(string $method, array $arguments = []) {
            if (!\method_exists($this, $method)) {
                return \call_user_func_array(array($this->initialize(), $method), $arguments);            
            }
        }
    }
}
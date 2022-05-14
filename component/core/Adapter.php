<?php
namespace Component\Core {
    abstract class Adapter extends \Component\Core {
        static protected $adapters = [];        

        abstract protected function initialize();
           
        public function __call(string $method, array $arguments = []) {
            if (!\method_exists($this, $method)) {
                return \call_user_func_array(array($this->initialize(), $method), $arguments);            
            }
        }
    }
}
<?php
namespace Components\Core {
    abstract class Adapter extends \Components\Core {
        public function __call($name, $arguments) {
            if (!method_exists($this, $name)) {
                return call_user_func_array(array($this->instance, $name), $arguments);            
            }
        }
    }
}
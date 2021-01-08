<?php
namespace Components\Core {
    abstract class Adapter extends \Components\Core {
        static private $_adapters = [];
        
        abstract protected function initialise();
        
        protected function hadAdapter($key) {
            return (bool) array_key_exists($key, self::$_adapters);
        }
        
        protected function getAdapter($key) {
            if ($this->hadAdapter($key)) {
                return self::$_adapters[$key];
            }
            
            throw new RuntimeException(sprintf("unknown or invalid instance by key %s", $key));
        }
        
        protected function addAdapter($key, $instance, $overwrite = true) {
            if ($overwrite || !$overwrite && $this->hasAdapter($key)) {
                return (object) self::$_adapters[$key] = $instance;
            }
            
            throw new RuntimeException(sprintf("found existing adapter by key %s", $key));
        }

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
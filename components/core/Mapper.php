<?php
namespace Components\Core {
    abstract class Mapper extends \Components\Core {
        static private $_instances = [];
        
        abstract protected function initialise();
        
        public function getInstance() {
            if (!array_key_exists($this->key, self::$_instances)) {
                self::$_instances[$this->key] = $this->initialise();
            }
            
            return (object) self::$_instances[$this->key];
        }
        
        final public function hasField(string $field) : bool {
            return (bool) array_key_exists($field, $this->mapping);
        }

        final public function getField(string $parameter) : string {
            if ($this->exists($parameter)) {
                return (string) array_search($parameter, $this->mapping);
            }
            
            throw new Event(sprintf("unknown parameter %s", $parameter));
        }

        final public function getParameter(string $field) : string {
            if ($this->hasField($field)) {
                return (string) $this->mapping[$field];
            }
            
            throw new Event(sprintf("unknown field %s", $field));
        }
        
        final public function __call($name, $arguments) {
            if (!method_exists($this, $name)) {
                return call_user_func_array(array($this->getInstance(), $name), $arguments);            
            }
        }
    }
}
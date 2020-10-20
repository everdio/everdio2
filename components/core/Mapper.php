<?php
namespace Components\Core {
    abstract class Mapper extends \Components\Core {
        static protected $instances = [];
        
        abstract protected function initialise();

        final public function uses(string $trait) : bool {
            return (bool) in_array($trait, class_uses($this));
        }
        
        final public function hasField(string $field) : bool {
            return (bool) array_key_exists($field, $this->mapping);
        }

        final public function getField(string $parameter) : string {
            if ($this->exists($parameter)) {
                return (string) array_search($parameter, $this->mapping);
            }
            
            throw new \LogicException (sprintf("unknown parameter %s", $parameter));
        }

        final public function getParameter(string $field) : string {
            if ($this->hasField($field)) {
                return (string) $this->mapping[$field];
            }
            
            throw new \LogicException (sprintf("unknown field %s", $field));
        }

        final public function __call($name, $arguments) {
            if (!method_exists($this, $name)) {
                return call_user_func_array(array($this->initialise(), $name), $arguments);            
            }
        }
    }
}
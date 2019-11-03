<?php
namespace Components\Core\Adapter {
    abstract class Mapper extends \Components\Core\Adapter {       
        final public function hasField(string $field) : bool {
            return (bool) isset($this->mapping) &&  array_key_exists($field, $this->mapping);
        }

        final public function getField(string $parameter) : string {
            if (isset($this->mapping) && $this->exists($parameter)) {
                return (string) array_search($parameter, $this->mapping);
            }
            
            throw new Event(sprintf("unknown parameter %s", $parameter));
        }

        final public function getParameter(string $field) : string {
            if (isset($this->mapping) && $this->hasField($field)) {
                return (string) $this->mapping[$field];
            }
            
            throw new Event(sprintf("unknown field %s", $field));
        }       
    }
}
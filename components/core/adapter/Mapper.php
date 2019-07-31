<?php
namespace Components\Core\Adapter {
    abstract class Mapper extends \Components\Core\Adapter {
        public function hasField(string $field) : bool {
            return (bool) isset($this->mapping) &&  array_key_exists($field, $this->mapping);
        }

        public function getField(string $parameter) : string {
            if (isset($this->mapping) && $this->exists($parameter)) {
                return (string) array_search($parameter, $this->mapping);
            }
            
            throw new Event(sprintf("unknown parameter %s", $parameter));
        }

        public function getParameter(string $field) : string {
            if ($this->hasField($field)) {
                return (string) $this->mapping[$field];
            }
            
            throw new Event(sprintf("unknown field %s", $field));
        }       
        
        public function hasMapping() : bool{
            return (bool) (isset($this->mapping) && in_array(true, $this->validate($this->mapping)));
        }
    }
}
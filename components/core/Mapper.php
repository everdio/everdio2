<?php
namespace Components\Core {
    abstract class Mapper extends \Components\Core {
        public function hasField(string $field) : bool {
            return (bool) isset($this->mapping) &&  array_key_exists($field, $this->mapping);
        }

        public function getField(string $parameter) : string {
            if (isset($this->mapping) && in_array($parameter, $this->mapping)) {
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
        
        public function getLabel() : string {
            foreach ($this->mapping as $parameter) {
                if (!isset($this($parameter)->{"Components\Validator\IsEmpty"}) && isset($this($parameter)->{"Components\Validator\IsString"}) && isset($this($parameter)->{"Components\Validator\Len\Smaller"}) && $this($parameter)("Components\Validator\Len\Smaller")->getLen() <= 255) {
                    return (string) $parameter;
                }
            }
        }      
        
        public function hasMapping() : bool {
            return (bool) (isset($this->mapping) && sizeof(array_filter($this->restore($this->mapping))));
        }

        public function isMapped() : array {
            return (array) array_filter($this->restore($this->mapping));
        }
        
        public function isKey(string $parameter) : bool {
            return (bool) (isset($this->keys) && in_array($parameter, $this->keys));
        }
        
        public function isRelation(string $parameter) : bool {
            return (bool) isset($this->relations) && array_key_exists($parameter, $this->relations);
        }
        
        public function connect(Mapper $mapper) {
            if (isset($this->relations) && in_array((string) $mapper, $this->relations)) {
                $this->store($mapper->restore($mapper->keys));
            }
        }

        public function __call($name, $arguments) {
            if (!method_exists($this, $name) && method_exists($this->resource, $name)) {
                return call_user_func_array(array($this->resource, $name), $arguments);            
            } elseif (!method_exists($this, $name)) {
                throw new Event(sprintf("unknown function call %s(%s)", $name, $this->dehydrate($arguments)));
            }
        }
    }
}
<?php
namespace Components\Core\Adapter {
    abstract class Mapper extends \Components\Core\Adapter {
        public function summarize(array $types, int $size = 2, array $parameters = []) : array {
            foreach ($this->parameters($this->mapping) as $parameter => $validation) {
                if (array_intersect($validation->getTypes(), $types) === $validation->getTypes()) {
                    $parameters[] = $parameter; 
                }
            }      
            return (array) array_slice($parameters, 0, $size);            
        }        
        
        final public function hasField(string $field) : bool {
            return (bool) (isset($this->mapping) && array_key_exists($field, $this->mapping));
        }

        final public function getField(string $parameter) : string {
            if (isset($this->mapping) && $this->exists($parameter)) {
                return (string) array_search($parameter, $this->mapping);
            }
            
            throw new \LogicException (sprintf("unknown parameter %s", $parameter));
        }
        
        final public function hasKey(string $parameter) : bool {
            return (bool) (isset($this->keys) && $this->exists($parameter) && in_array($parameter, $this->keys));
        }
        
        final public function getKey(string $parameter) : string {
            if ($this->hasKey($parameter)) {
                return (string) array_search($parameter, $this->keys);
            }
            
            throw new \LogicException (sprintf("unknown key %s", $parameter));
        }
        
        final public function hasRelation(string $mapper) : bool {
            return (bool) (isset($this->relations) && in_array($mapper, $this->relations));
        }
        
        final public function getRelation(string $mapper) : string {
            if ($this->hasRelation($mapper)) {
                return (string) array_search($mapper, $this->relations);
            }
            
            throw new \LogicException (sprintf("unknown relation %s", $mapper));
        }

        final public function getParameter(string $field) : string {
            if ($this->hasField($field)) {
                return (string) $this->mapping[$field];
            }
            
            throw new \LogicException (sprintf("unknown field %s", $field));
        }
    }
}
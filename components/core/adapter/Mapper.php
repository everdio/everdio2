<?php
namespace Components\Core\Adapter {
    abstract class Mapper extends \Components\Core\Adapter {
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
        
        final public function hasRelation(string $key) : bool {
            return (bool) (isset($this->relations) && array_key_exists($key, $this->relations));
        }
        
        final public function getRelation(string $key) : string {
            if ($this->hasRelation($key)) {
                return (string) $this->relations[$key];
            }
            
            throw new \LogicException (sprintf("unknown relation %s", $mapper));
        }

        final public function getParameter(string $field) : string {
            if ($this->hasField($field)) {
                return (string) $this->mapping[$field];
            }
            
            throw new \LogicException (sprintf("unknown field %s", $field));
        }
        
        final public function summarize(array $types, int $size = 2) : array {
            return (array) array_slice(array_keys($this->byTypes($types)), 0, $size);            
        }          

        final public function byTypes(array $types, array $parameters = []) : array {
            foreach ($this->parameters($this->mapping) as $parameter => $validation) {
                if (array_intersect($validation->getTypes(), $types) === $validation->getTypes()) {    
                    $parameters[$parameter] = $validation;
                }
            }             
            return (array) $parameters;
        }        
    }
}
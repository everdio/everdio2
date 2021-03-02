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
            
            throw new \LogicException (sprintf("unknown key by parameter %s", $parameter));
        }
        
        final public function hasRelation(string $parameter) : bool {
            return (bool) ($this->hasKey($parameter) && isset($this->parents) && array_key_exists($this->getKey($parameter), $this->parents));
        }

        final public function getRelation(string $parameter) : self {
            if ($this->hasRelation($parameter)) {
                return (object) new $this->parents[$this->getKey($parameter)];
                //return (object) ((string) $this === $this->parents[$this->getKey($parameter)] ? clone $this : new $this->parents[$this->getKey($parameter)]);
            }
            
            throw new \LogicException (sprintf("unknown relation by parameter %s", $parameter));
        }        
        
        final public function hasForeign($key) : bool {
            return (bool) (isset($this->keys) && array_key_exists($key, $this->keys));
        }
        
        final public function getForeign($key) : string {
            if ($this->hasForeign($key)) {
                return (string) $this->keys[$key];
            }
            
            throw new \LogicException (sprintf("unknown foreign key %s", $key));
        }

        final public function getParameter(string $field) : string {
            if ($this->hasField($field)) {
                return (string) $this->mapping[$field];
            }
            throw new \LogicException (sprintf("unknown field %s", $field));
        }
        
        final public function view(array $types, int $size = 2) : string {
            return (string) implode(", ", $this->restore(array_slice(array_keys($this->label($types)), 0, $size)));
        }
        
        final public function label(array $types, array $parameters = []) : array {
            if (isset($this->mapping)) {
                foreach ($this->parameters($this->mapping) as $parameter => $validation) {
                    if (array_intersect($validation->types, $types) === $validation->types) {
                        $parameters[$parameter] = $validation;
                    }
                }
            }
            
            return (array) $parameters;
        }
        
        final public function __dry() : string {
            return (string) sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->restore($this->mapping)));
        }              
    }
}
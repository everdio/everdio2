<?php
namespace Component\Core\Adapter {
    abstract class Mapper extends \Component\Core\Adapter {
        final public function hasField(string $field) : bool {
            return (bool) (isset($this->mapping) && array_key_exists($field, $this->mapping));
        }

        final public function getField(string $parameter) : string {
            if (isset($this->mapping) && $this->exists($parameter)) {
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
               
        final public function hasParent(string $parameter) : bool {
            return (bool) (isset($this->parents) && array_key_exists($parameter, $this->parents));
        }

        final public function getParent(string $parameter) : self {
            if ($this->hasParent($parameter)) {
                return (object) new $this->parents[$parameter];
            }
            
            throw new \LogicException (sprintf("unknown parent by key %s", $parameter));
        }
        
        final public function hasKey(string $parameter) : bool {
            return (bool) (isset($this->keys) && array_key_exists($parameter, $this->keys));
        }
        
        final public function getKey(string $parameter) : string {
            if ($this->hasKey($parameter)) {
                return (string) $this->keys[$parameter];
            }
            
            throw new \LogicException (sprintf("unknown key by parameter %s", $parameter));
        }        
        
        final public function view(array $types, int $size = 2) : string {
            return (string) implode(", ", $this->restore(array_slice(array_keys($this->label($types)), 0, $size)));
        }
        
        final public function label(array $types, $sizeof = false, array $parameters = []) : array {
            if (isset($this->mapping)) {
                foreach ($this->parameters($this->mapping) as $parameter => $validation) {
                    if ($validation->match($types)) {
                        $parameters[$parameter] = $validation;
                    }
                }
            }
            
            return (array) ($sizeof ? array_slice($parameters, 0, $sizeof) : $parameters);
        }
        
        final public function __dry() : string {
            return (string) sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->restore($this->mapping)));
        }              
    }
}
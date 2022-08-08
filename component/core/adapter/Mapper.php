<?php
namespace Component\Core\Adapter {
    abstract class Mapper extends \Component\Core\Adapter {
        final public function hasField(string $field) : bool {
            return (bool) (isset($this->mapping) && \array_key_exists($field, $this->mapping));
        }

        final public function getField(string $parameter) : string {
            if (isset($this->mapping) && $this->exists($parameter)) {
                return (string) \array_search($parameter, $this->mapping);
            }
            
            throw new \LogicException (sprintf("unknown parameter %s", $parameter));
        }
        
        final public function hasMapping($parameter) : bool {
            return (bool) isset($this->mapping) && $this->exists($parameter) && \in_array($parameter, $this->mapping);
        }
        
        final public function getParameter(string $field) : string {
            if ($this->hasField($field)) {
                return (string) $this->mapping[$field];
            }
            
            throw new \LogicException(\sprintf("unknown field %s", $field));
        }        
     
        final public function isValid() {
            return (bool) ((isset($this->primary) && \sizeof($this->primary) === \sizeof($this->restore($this->primary)))) || (isset($this->keys) && \sizeof($this->keys) === \sizeof($this->restore($this->keys)));
        }
        
        final public function isParent(string $parameter) : bool {
            return (bool) ($this->isKey($parameter) && isset($this->parents) && \array_key_exists($parameter, $this->parents));
        }        
        
        final public function isPrimary(string $parameter) : bool {
            return (bool) (isset($this->primary) && \in_array($parameter, $this->primary));
        }        
        
        final public function isKey(string $parameter) : bool {
            return (bool) (isset($this->keys) && \array_key_exists($parameter, $this->keys));
        }        
        
        final public function hasKey(string $parameter) : bool {
            return (bool) (isset($this->keys) && \array_key_exists($parameter, $this->keys));
        }
        
        final public function view(array $types = [\Component\Validator\IsString::TYPE], int $sizeof = 1) : string {
            return (string) $this->desanitize(\strip_tags(\implode(", ", \array_filter($this->restore(\array_keys($this->label($types, $sizeof)))))));
        }
        
        final public function label(array $types = [\Component\Validator\IsString::TYPE], int $sizeof = 1, array $parameters = []) : array {
            if (isset($this->mapping)) {
                foreach ($this->parameters($this->mapping) as $parameter => $validation) {
                    if ($validation->has($types)) {
                        $parameters[$parameter] = $validation;
                    }
                }
            }
            return (array) \array_diff_key($parameters, \array_slice(\array_reverse($parameters), \round($sizeof / 2), \sizeof($parameters) - $sizeof));
        }        

        final public function __dry() : string {
            return (string) \sprintf("new \%s(%s)", (string) $this, (isset($this->mapping) ? $this->dehydrate($this->restore($this->mapping)) : false));
        }              
    }
}
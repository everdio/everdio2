<?php
namespace Component\Core {
    class Parameters extends \Component\Core {
        final public function __set(string $parameter, $value) {
            return $this->set($parameter, $value);
        }
        
        final public function set(string $field, $value) : void {
            $parameter = new \Component\Validation\Parameter($value, true);
            $this->add($field, $parameter->getValidation($parameter->getValidators()), true);           
        }

        final public function store(array $values) : self {
            foreach ($values as $field => $value) {
                if (\is_array($value)) {
                    if (!isset($this->{$field})) {
                        $this->{$field} = new self;
                    }
                    
                    $this->{$field}->store($value);
                } else {
                    $this->{$field} = $value;
                }
            }
            
            return (object) $this;
        }
        
        final public function restore(array $parameters = [], array $values = []) : array {
            foreach (parent::restore($this->diff($parameters), $values) as $field => $value) {
                $values[$field] = ($value instanceof self ? $value->restore() : $value);
            }

            return (array) $values;
        }        
        
        final public function arguments(array $parameters = []) : string {
            return (string) \http_build_query([$this->restore($this->diff($parameters))]);
        }

        final public function implode(string $seperator = ", ") : string {
            return (string) \implode($seperator, (array) $this->restore());
        }        
    }
}
<?php
namespace Component\Core {
    class Parameters extends \Component\Core {
        public function __set(string $field, $value) : void {
            $parameter = new \Component\Validation\Parameter($value, true);
            $this->add($field, $parameter->getValidation($parameter->getValidators()), true);
        }
    
        public function store(array $values) : self {
            foreach ($values as $field => $value) {
                if (\is_array($value)) {
                    $this->{$field} = new self;
                    $this->{$field}->store($value);
                } else {
                    $this->{$field} = $value;
                }
            }
            
            return (object) $this;
        }
        
        public function restore(array $parameters = [], array $values = []) : array {
            foreach (parent::restore($this->diff($parameters), $values) as $field => $value) {
                $values[$field] = ($value instanceof self ? $value->restore() : $value);
            }

            return (array) $values;
        }        
        
        public function arguments(array $parameters = []) : string {
            return (string) \http_build_query([$this->restore($this->diff($parameters))]);
        }
    }
}
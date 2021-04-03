<?php
namespace Component\Core {
    class Parameters extends \Component\Core {
        public function __set(string $field, $value) : void {
            $parameter = new \Component\Validation\Parameter($field, $value, true);
            $this->add($field, $parameter->getValidation($parameter->getValidators()), true);
        }
        
        public function store(array $values) {
            foreach ($values as $field => $value) {
                $this->{$field} = $value;
            }
        }
        
        public function restore(array $parameters = [], array $values = []) : array {
            return (array) parent::restore($this->inter($parameters), $values);
        }

        final public function search(string $path) {    
            foreach (explode(DIRECTORY_SEPARATOR, $path) as $parameter) {   
                return (isset($this->{$parameter}) ? ($this->{$parameter} instanceof self ? $this->{$parameter}->search(implode(DIRECTORY_SEPARATOR, array_diff(explode(DIRECTORY_SEPARATOR, $path), [$parameter]))) : $this->{$parameter}) : false);
            }        
        }
    }
}
<?php
namespace Component\Core {
    class Parameters extends \Component\Core {
        public function __set(string $field, $value) : void {
            $parameter = new \Component\Validation\Parameter($value, true);
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
    }
}
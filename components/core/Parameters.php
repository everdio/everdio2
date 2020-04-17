<?php
namespace Components\Core {
    class Parameters extends \Components\Core {
        public function __set(string $field, $value) : bool {
            $parameter = new \Components\Validation\Parameter($field, $value, true);
            return (bool) $this->add($field, $parameter->getValidation($parameter->getValidators()), true);
        }
        
        public function store(array $values) {
            foreach ($values as $field => $value) {
                $this->{$field} = $value;
            }
        }
        
        public function restore(array $parameters = [], array $values = []) : array {
            return (array) parent::restore($this->diff($parameters), $values);
        }
    }
}
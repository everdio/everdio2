<?php
namespace Components\Core {
    class Request extends \Components\Core {
        public function __set(string $field, $value) : bool {
            $parameter = new \Components\Validation\Parameter($field, $value, true);
            return (bool) $this->add($field, $parameter->getValidation($parameter->getValidators()));
        }
        
        public function store(array $values) {
            foreach ($values as $field => $value) {
                $this->{$field} = $value;
            }
        }
    }
}
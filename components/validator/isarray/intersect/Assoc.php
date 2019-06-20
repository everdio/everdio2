<?php
namespace Components\Validator\IsArray\Intersect {
    class Assoc extends \Components\Validator\IsArray\Intersect {
        const MESSAGE = "ARRAYS_NOT_MATCHING";
        public function execute($value) : bool {
            return (bool) (\Components\Validator\IsArray::execute($value) && array_intersect_assoc($value, $this->array) === $value);
        }
    }
}


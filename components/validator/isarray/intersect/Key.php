<?php
namespace Components\Validator\IsArray\Intersect {
    class Key extends \Components\Validator\IsArray\Intersect {
        const MESSAGE = "ARRAY_KEYS_NOT_MATCHING";
        public function execute($value) : bool {
            return (bool) (\Components\Validator\IsArray::execute($value) && sizeof(array_intersect_key($value, array_flip($this->array))) >= sizeof($this->array));
        }
    }
}


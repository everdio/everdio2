<?php

namespace Component\Validator\IsArray\Intersect {

    class Key extends \Component\Validator\IsArray\Intersect {

        public function execute($value): bool {
            return (bool) (\Component\Validator\IsArray::execute($value) && \sizeof(\array_intersect_key($value, \array_flip($this->array))) >= \sizeof($this->array));
        }
    }

}


<?php

namespace Component\Validator\IsArray\Intersect {

    class Assoc extends \Component\Validator\IsArray\Intersect {

        public function execute($value): bool {
            return (bool) (\Component\Validator\IsArray::execute($value) && \array_intersect_assoc($value, $this->array) === $value);
        }
    }

}


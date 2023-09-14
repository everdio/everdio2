<?php

namespace Component\Validator\IsArray\Sizeof {

    class Smaller extends \Component\Validator\IsArray\Sizeof {

        public function execute($value): bool {
            return (bool) (parent::execute($value) && \sizeof($value) <= $this->sizeof);
        }
    }

}


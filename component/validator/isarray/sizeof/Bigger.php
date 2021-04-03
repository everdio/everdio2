<?php
namespace Component\Validator\IsArray\Sizeof {
    class Bigger extends \Component\Validator\IsArray\Sizeof {
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && sizeof($value) >= $this->sizeof);
        }
    }
}


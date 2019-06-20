<?php
namespace Components\Validator\IsArray\Sizeof {
    class Bigger extends \Components\Validator\IsArray\Sizeof {
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && sizeof($value) >= $this->sizeof);
        }
    }
}


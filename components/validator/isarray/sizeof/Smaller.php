<?php
namespace Components\Validator\IsArray\Sizeof {
    class Smaller extends \Components\Validator\IsArray\Sizeof {
        public function execute($value) : bool {
            return (bool) (parent::execute($value) && sizeof($value) <= $this->sizeof);
        }
    }
}


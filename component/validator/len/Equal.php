<?php
namespace Component\Validator\Len {
    class Equal extends \Component\Validator\Len {
        public function execute($value) : bool {            
            return (bool) \strlen($value) === $this->len;
        }
    }
}


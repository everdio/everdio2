<?php
namespace Components\Validator\Len {
    class Equal extends \Components\Validator\Len {
        public function execute($value) : bool {            
            return (bool) (strlen($value) === $this->len);
        }
    }
}


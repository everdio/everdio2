<?php
namespace Components\Validator\IsNumeric {
    class Decimal extends \Components\Validator\IsNumeric {
        public function execute($value) : bool {
            return (bool) parent::execute($value) && !ctype_digit($value);
        }
    }
}
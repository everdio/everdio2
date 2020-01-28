<?php
namespace Components\Validator\IsString\IsDatetime {
    class Timestamp extends \Components\Validator\IsString\IsDatetime {
        public function __construct($format = "U") {
            parent::__construct($format);
        }
    }
}
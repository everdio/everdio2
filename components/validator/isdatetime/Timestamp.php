<?php
namespace Components\Validator\IsDatetime {
    class Timestamp extends \Components\Validator\IsDatetime {
        public function __construct($format = "U") {
            parent::__construct($format);
        }
    }
}
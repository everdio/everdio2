<?php
namespace Components\Validator\IsDatetime {
    class Timestamp extends \Components\Validator\IsDatetime {
        const TYPE = "IS_TIMESTAMP";
        public function __construct($format = "U") {
            parent::__construct($format);
        }
    }
}
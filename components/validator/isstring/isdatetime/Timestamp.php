<?php
namespace Components\Validator\IsString\IsDatetime {
    class Timestamp extends \Components\Validator\IsString\IsDatetime {
        const TYPE = "IS_TIMESTAMP";
        public function __construct($format = "U") {
            parent::__construct($format);
        }
    }
}
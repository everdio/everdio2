<?php
namespace Components\Validator\IsString\IsDatetime {
    class IsDate extends \Components\Validator\IsString\IsDatetime {
        public function __construct($format = "Y-m-d") {
            parent::__construct($format);
        }        
    }
}
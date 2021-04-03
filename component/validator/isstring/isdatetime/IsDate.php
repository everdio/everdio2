<?php
namespace Component\Validator\IsString\IsDatetime {
    class IsDate extends \Component\Validator\IsString\IsDatetime {
        const TYPE = "IS_DATE";
        public function __construct($format = "Y-m-d") {
            parent::__construct($format);
        }        
    }
}
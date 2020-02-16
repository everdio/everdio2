<?php
namespace Components\Validator\IsString\IsDatetime {
    class IsDate extends \Components\Validator\IsString\IsDatetime {
        const TYPE = "IS_DATE";
        public function __construct($format = "Y-m-d") {
            parent::__construct($format);
        }        
    }
}
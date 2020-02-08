<?php
namespace Components\Validator\IsDatetime {
    class IsDate extends \Components\Validator\IsDatetime {
        const TYPE = "IS_DATE";
        public function __construct($format = "Y-m-d") {
            parent::__construct($format);
        }        
    }
}
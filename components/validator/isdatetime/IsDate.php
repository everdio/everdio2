<?php
namespace Components\Validator\IsDatetime {
    class IsDate extends \Components\Validator\IsDatetime {
        public function __construct($format = "Y-m-d") {
            parent::__construct($format);
        }        
    }
}
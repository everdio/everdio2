<?php

namespace Component\Validator\IsString\IsDatetime {

    class Timestamp extends \Component\Validator\IsString\IsDatetime {

        const TYPE = "IS_TIMESTAMP";

        public function __construct($format = "U") {
            parent::__construct($format);
        }
    }

}
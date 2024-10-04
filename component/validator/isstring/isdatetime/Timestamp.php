<?php

namespace Component\Validator\IsString\IsDatetime {

    class Timestamp extends \Component\Validator\IsString\IsDatetime {

        const TYPE = "timestamp";

        public function __construct($format = "U") {
            parent::__construct($format);
        }
    }

}
<?php

namespace Component\Validator\IsString\IsDatetime {

    class IsDate extends \Component\Validator\IsString\IsDatetime {

        const TYPE = "date";

        public function __construct(string $format = "Y-m-d") {
            parent::__construct($format);
        }
    }

}
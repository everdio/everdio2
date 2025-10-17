<?php

namespace Modules\Table {

    final class Count extends \Component\Validation {

        public function __construct() {
            parent::__construct(["COUNT(*)"], [new \Component\Validator\IsArray]);
        }
    }

}
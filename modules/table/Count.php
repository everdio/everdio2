<?php

namespace Modules\Table {

    final class Count extends \Component\Validation {

        public function __construct(string $count = "*") {
            parent::__construct(\sprintf(" COUNT(DISTINCT %s) ", $count), [new \Component\Validator\NotEmpty]);
        }
    }

}
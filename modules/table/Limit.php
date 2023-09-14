<?php

namespace Modules\Table {

    final class Limit extends \Component\Validation {

        public function __construct(int $position, int $limit) {
            parent::__construct(\sprintf(" LIMIT %s,%s", $position, $limit), [new \Component\Validator\IsString]);
        }
    }

}
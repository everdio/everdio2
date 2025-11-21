<?php

namespace Modules\Node {

    use \Component\Validator;

    final class Position extends \Component\Validation {

        public function __construct(int $limit, int $position = 0) {
            parent::__construct(\sprintf("position() >= %d and position() <= %d", $position, $position + $limit), [new Validator\IsString]);
        }
    }

}
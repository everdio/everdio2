<?php

namespace Modules\Node {

    use \Component\Validator;

    final class Position extends \Component\Validation {

        public function __construct(string $path, int $limit, int $position = 0) {
            parent::__construct(\sprintf("position() >= %s and position() <= %s", $path, $position, $position + $limit), [new Validator\IsString]);
        }
    }

}
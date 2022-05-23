<?php
namespace Modules\Node {
    use \Component\Validator;
    final class Position extends \Component\Validation {
        public function __construct(string $path, int $position, int $limit) {
            parent::__construct(sprintf("%s[position() >= %s and position() <= %s]", $path, $position, $position + $limit), [new Validator\IsString]);
        }
    }
}
<?php
namespace Modules\Node {
    use \Component\Validator;
    final class Min extends \Component\Validation {
        public function __construct(string $tag, string $field) {
            parent::__construct(\sprintf("not(.>../%s/@%s)", $tag, $field), [new Validator\IsString]);
        }
    }
}
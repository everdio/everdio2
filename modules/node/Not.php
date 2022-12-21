<?php
namespace Modules\Node {
    use \Component\Validator;
    final class Not extends \Component\Validation {
        public function __construct(string $tag, string $attribute, string $expression = "=") {
            parent::__construct(\sprintf("[not(. %s ../../%s/@%s)]", $expression, $tag, $attribute), [new Validator\IsString]);
        }
    }
}
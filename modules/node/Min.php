<?php
namespace Modules\Node {
    use \Component\Validator;
    final class Min extends \Component\Validation {
        public function __construct(\Component\Core $mapper, string $parameter) {
            parent::__construct(\sprintf("[@%s[not(. < ../%s/@%s)]][1]", $mapper->getField($parameter), \strtolower($mapper->label), $mapper->getField($parameter)), [new Validator\IsString]);
        }
    }
}
<?php
namespace Modules\Node {
    use Components\Validator;
    final class Attribute extends \Components\Core\Parameter {
        public function getValidators(array $validators = []) : array {
            $validators[] = new Validator\IsString;
            $validators[] = new Validator\IsInteger;
            return (array) $validators;
        }
    }
}
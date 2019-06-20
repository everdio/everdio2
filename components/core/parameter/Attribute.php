<?php
namespace Components\Core\Parameter {
    use Components\Validator;
    class Attribute extends \Components\Core\Parameter {
        public function getValidators(array $validators = []) : array {
            $validators[] = new Validator\IsString;
            $validators[] = new Validator\IsInteger;
            $validators[] = new Validator\IsEmpty;
            return (array) $validators;
        }
    }
}
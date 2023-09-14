<?php

namespace Modules\Node {

    final class Filter extends \Component\Validation {

        public function __construct(string $xpath, array $validations = [], string $operator = "and", array $xparts = []) {
            foreach ($validations as $validation) {
                if (($validation instanceof \Component\Validation && $validation->isValid())) {
                    $xparts[] = $validation->execute();
                }
            }

            if (\sizeof($xparts)) {
                $xpath = \sprintf("%s[%s]", $xpath, \implode(\sprintf(" %s ", $operator), $xparts));
            }

            parent::__construct($xpath, [new \Component\Validator\IsString]);
        }
    }

}
<?php

namespace Modules\Table {

    use \Component\Validator;

    final class Operators extends \Component\Validation {

        public function __construct(array $operators = [], string $operator = "AND",) {
            parent::__construct((\sizeof($operators) ? \trim(\sprintf("WHERE %s", \implode(\sprintf(" %s ", \strtoupper($operator)), $operators))) : false), [new Validator\IsString, new Validator\IsBool]);
        }
    }

}


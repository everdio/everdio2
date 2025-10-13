<?php

namespace Modules\Table {

    final class Filter extends \Component\Validation {

        public function __construct(array $mappers, array $parameters, string $operator = "and", string $expression = "=", array $operators = []) {
            foreach ($mappers as $mapper) {
                if ($mapper instanceof \Component\Core\Adapter\Mapper) {
                    $operators = \array_merge($operators, (new Conditions($mapper, $parameters, $expression))->execute());
                }
            }

            parent::__construct(\trim(\implode(\sprintf(" %s ", \strtoupper($operator)), $operators)), [new \Component\Validator\NotEmpty]);
        }
    }

}
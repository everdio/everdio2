<?php

namespace Modules\Table {

    final class Filter extends \Component\Validation {

        public function __construct(array $mappers, string $operator = "and", string $expression = "=", array $operators = []) {
            foreach ($mappers as $mapper) {
                if ($mapper instanceof \Component\Core\Adapter\Mapper && isset($mapper->mapping)) {
                    foreach ((\sizeof($mapper->restore($mapper->primary)) === \sizeof($mapper->primary) ? $mapper->restore($mapper->primary) : $mapper->restore($mapper->mapping)) as $parameter => $value) {
                        if (!empty($value) || !\is_bool($value)) {
                            $operators[] = \sprintf("%s %s :%s_%s", (new Column($mapper, $parameter))->execute(), $expression, $mapper->table, $mapper->getField($parameter));
                        }
                    }
                }
            }

            parent::__construct(\implode(\sprintf(" %s ", \strtoupper($operator)), $operators), [new \Component\Validator\IsString]);
        }
    }

}
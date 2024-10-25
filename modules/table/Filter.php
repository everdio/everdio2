<?php

namespace Modules\Table {

    use \Component\Validator;

    final class Filter extends \Component\Validation {

        public function __construct(array $mappers, string $operator = "and", string $expression = "=", array $operators = []) {
            foreach ($mappers as $mapper) {
                
                if ($mapper instanceof \Component\Core\Adapter\Mapper && isset($mapper->mapping)) {
                    foreach ((\sizeof($mapper->restore($mapper->primary)) === \sizeof($mapper->primary) ? $mapper->restore($mapper->primary) : $mapper->restore($mapper->mapping)) as $parameter => $value) {
                        if (!empty($value) || !$mapper->getParameter($parameter)->hasTypes([Validator\IsBool::TYPE])) {
                            $column = (\substr($mapper->getField($parameter), 0, 1) == '@' ? \sprintf("%s :", $mapper->getField($parameter)) : (new Column($mapper, $parameter))->execute());

                            if ($mapper->getParameter($parameter)->hasTypes([Validator\IsInteger::TYPE, Validator\IsNumeric::TYPE])) {
                                $operators[] = \sprintf("%s %s %s ", $column, $expression, $value);
                            } elseif ($mapper->getParameter($parameter)->hasTypes([Validator\IsString::TYPE, Validator\IsString\IsDatetime::TYPE])) {
                                $operators[] = \sprintf("%s %s '%s'", $column, $expression, $this->sanitize($value));
                            } elseif ($mapper->getParameter($parameter)->hasTypes([Validator\IsArray::TYPE])) {
                                $sets = [];

                                foreach ($value as $set) {
                                    $sets[] = \sprintf(" FIND_IN_SET('%s',%s) ", $set, $column);
                                }

                                $operators[] = \implode("AND", $sets);
                            }
                        }
                    }
                }
            }

            parent::__construct(\trim(\implode(\sprintf(" %s ", \strtoupper($operator)), $operators)), [new Validator\IsString]);
        }
    }

}
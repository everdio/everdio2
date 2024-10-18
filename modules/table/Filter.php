<?php

namespace Modules\Table {

    use \Component\Validator,
        \Component\Core\Adapter\Mapper;

    final class Filter extends \Component\Validation {

        public function __construct(array $mappers, string $operator = "and", string $expression = "=", array $operators = []) {
            foreach ($mappers as $mapper) {
                if ($mapper instanceof Mapper && isset($mapper->mapping)) {
                    if (\sizeof($mapper->restore($mapper->primary)) === \sizeof($mapper->primary)) {
                        foreach ($mapper->restore($mapper->primary) as $parameter => $value) {
                            $operators[] = \sprintf("%s %s %s ", (new Column($mapper, $parameter))->execute(), $expression, $value);
                        }
                    } else {
                        foreach ($mapper->restore($mapper->mapping) as $parameter => $value) {
                            if (!empty($value) || !$mapper->getParameter($parameter)->hasTypes([Validator\IsBool::TYPE])) {
                                $column = (\substr($mapper->getField($parameter), 0, 1) == '@' ? \sprintf("%s :", $mapper->getField($parameter)) : (new Column($mapper, $parameter))->execute());

                                if ($mapper->getParameter($parameter)->hasTypes([Validator\IsInteger::TYPE, Validator\IsNumeric::TYPE])) {
                                    $operators[] = \sprintf("%s %s %s ", $column, $expression, $value);
                                } elseif ($mapper->getParameter($parameter)->hasTypes([Validator\IsString::TYPE, Validator\IsString\IsDateTime::TYPE])) {
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
            }

            parent::__construct(\implode(\sprintf("\n%s\n\t", \strtoupper($operator)), $operators), [new Validator\IsString]);
        }
    }

}
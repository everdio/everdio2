<?php

namespace Modules\Table {

    use \Component\Validator,
        \Component\Core\Adapter\Mapper;

    final class Filter extends \Component\Validation {

        public function __construct(array $mappers, string $operator = "and", string $expression = "=", array $operators = []) {
            foreach ($mappers as $mapper) {
                if ($mapper instanceof Mapper && isset($mapper->mapping)) {
                    foreach ($mapper->restore($mapper->mapping) as $parameter => $value) {
                        if (!empty($value) || !isset($mapper->getParameter($parameter)->empty)) {
                            $column = (\substr($mapper->getField($parameter), 0, 1) == '@' ? \sprintf("%s :", $mapper->getField($parameter)) : (new Column($mapper, $parameter))->execute());
                            
                            if (isset($mapper->getParameter($parameter)->numeric)) {
                                $operators[] = \sprintf("%s %s %s ", $column, $expression, $value);
                            } elseif (isset($mapper->getParameter($parameter)->string) || isset($mapper->getParameter($parameter)->default) || isset($mapper->getParameter($parameter)->date)) {
                                $operators[] = \sprintf("%s %s '%s'", $column, $expression, $this->sanitize($value));
                            } elseif (isset($mapper->getParameter($parameter)->array)) {
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

            parent::__construct(\implode(\strtoupper($operator), $operators), [new Validator\IsString]);
        }
    }

}
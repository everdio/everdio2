<?php

namespace Modules\Table {

    use \Component\Validator;

    final class Select extends \Component\Validation {

        public function __construct(array $mappers, array $select = []) {
            foreach ($mappers as $mapper) {
                if ($mapper instanceof \Component\Core\Adapter\Mapper && isset($mapper->mapping)) {
                    foreach ($mapper->inter($mapper->mapping) as $parameter) {
                        $select[$parameter] = \sprintf(" %s AS`%s`", (\substr($mapper->getField($parameter), 0, 1) == '@' ? $mapper->getField($parameter) : \sprintf("`%s`.`%s`.`%s`", $mapper->database, $mapper->table, $mapper->getField($parameter))), $parameter);
                    }
                }
            }

            parent::__construct(\sprintf("SELECT%s", \implode(",", $select)), [new Validator\IsString]);
        }
    }

}
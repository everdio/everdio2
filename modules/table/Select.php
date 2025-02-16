<?php

namespace Modules\Table {

    final class Select extends \Component\Validation {

        public function __construct(array $mappers, array $select = []) {
            foreach (\array_reverse($mappers) as $mapper) {
                if ($mapper instanceof \Component\Core\Adapter\Mapper && isset($mapper->mapping)) {
                    foreach ($mapper->inter($mapper->mapping) as $parameter) {
                        $select[$parameter] = \sprintf("%s AS %s", (new Column($mapper, $parameter))->execute(), $parameter);
                    }
                }
            }

            parent::__construct(\implode(", ", $select), [new \Component\Validator\IsString]);
        }
    }

}
<?php

namespace Modules\Table {

    use \Component\Validator;

    final class From extends \Component\Validation {

        public function __construct(array $mappers, array $from = []) {
            foreach ($mappers as $mapper) {
                if ($mapper instanceof \Component\Core\Adapter\Mapper) {
                    $from[] = $mapper->resource;
                }
            }
            
            parent::__construct(\sprintf("FROM %s", \implode(", ", $from)), [new Validator\IsString]);
        }
    }

}
<?php

namespace Modules\Table {

    use \Component\Validator;

    final class From extends \Component\Validation {

        public function __construct(array $mappers, array $from = []) {
            foreach ($mappers as $mapper) {
                if ($mapper instanceof \Component\Core) {
                    $from[] = $mapper->resource;
                }
            }

            parent::__construct(\sprintf("FROM\n\t%s", \implode(",\n\t", $from)), [new Validator\IsString]);
        }
    }

}
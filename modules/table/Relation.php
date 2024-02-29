<?php

namespace Modules\Table {

    use \Component\Validator,
        \Component\Core\Adapter\Mapper;

    final class Relation extends \Component\Validation {

        public function __construct(Mapper $mapper, array $mappers, string $join = "join", string $operator = "and", array $relations = []) {
            foreach ($mappers as $thatMapper) {
                if ($thatMapper instanceof Mapper && !$mapper instanceof $thatMapper) {
                    if (isset($mapper->primary) && isset($thatMapper->keys) && \sizeof(($keys = \array_intersect($mapper->primary, $thatMapper->keys)))) {
                        $relations[] = (new Join($thatMapper, $mapper, \array_intersect($thatMapper->keys, $keys), $join, $operator))->execute();
                    } elseif (isset($thatMapper->primary) && isset($mapper->keys) && \sizeof(($keys = \array_intersect($thatMapper->primary, $mapper->keys)))) {
                        $relations[] = (new Join($thatMapper, $mapper, \array_flip(\array_intersect($mapper->keys, $keys)), $join, $operator))->execute();
                    }
                }
            }
            
            parent::__construct(\implode(\PHP_EOL, $relations), [new Validator\IsEmpty, new Validator\IsString\Contains(["JOIN"])]);
        }
    }

}
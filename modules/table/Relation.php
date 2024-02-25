<?php

namespace Modules\Table {

    use \Component\Validator,
        \Component\Core\Adapter\Mapper;

    final class Relation extends \Component\Validation {

        public function __construct(Mapper $mapper, array $mappers, string $join = "join", string $operator = "and", array $relations = []) {
            foreach ($mappers as $thatMapper) {
                if ($thatMapper instanceof Mapper && !$mapper instanceof $thatMapper) {
                    if (isset($thatMapper->primary) && isset($mapper->keys) && \sizeof(\array_intersect($thatMapper->primary, $mapper->keys))) {
                        $relation = new Join($thatMapper, $mapper, \array_intersect($mapper->keys, \array_intersect($thatMapper->primary, $mapper->keys)), \strtoupper($join), \strtoupper($operator));
                        $relations[] = $relation->execute();
                    } elseif (isset($mapper->primary) && isset($thatMapper->keys) && \sizeof(\array_intersect($mapper->primary, $thatMapper->keys))) {
                        $relation = new Join($thatMapper, $mapper, \array_intersect($thatMapper->keys, \array_intersect($mapper->primary, $thatMapper->keys)), \strtoupper($join), \strtoupper($operator));
                        $relations[] = $relation->execute();
                    }
                }
            }
            parent::__construct(\implode(\PHP_EOL, $relations), [new Validator\IsEmpty, new Validator\IsString\Contains(["JOIN"])]);
        }
    }

}
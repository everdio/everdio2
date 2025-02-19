<?php

namespace Modules\Table {

    use \Component\Validator,
        \Component\Core\Adapter\Mapper;

    final class Relation extends \Component\Validation {

        public function __construct(Mapper $thisMapper, array $thatMappers, string $join = "join", string $operator = "AND", array $relations = []) {
            foreach ($thatMappers as $thatMapper) {
                if ($thatMapper instanceof Mapper && !$thisMapper instanceof $thatMapper) {
                    if (isset($thisMapper->primary) && isset($thatMapper->keys) && \sizeof(($keys = \array_intersect($thisMapper->primary, $thatMapper->keys)))) {
                        $relations[] = $this->join($thatMapper, $thisMapper, \array_intersect($thatMapper->keys, $keys), $join, \strtoupper($operator));
                    } elseif (isset($thatMapper->primary) && isset($thisMapper->keys) && \sizeof(($keys = \array_intersect($thatMapper->primary, $thisMapper->keys)))) {
                        $relations[] = $this->join($thatMapper, $thisMapper, \array_flip(\array_intersect($thisMapper->keys, $keys)), $join, \strtoupper($operator));
                    }
                }
            }

            parent::__construct(\trim(\implode(" ", $relations)), [new Validator\IsEmpty, new Validator\IsString\Contains(["JOIN"])]);
        }

        private function join(Mapper $thatMapper, Mapper $thisMapper, array $keys, string $join = "join", string $operator = "AND", array $operators = [], ?string $filter = NULL) {
            foreach ($keys as $thatKey => $thisKey) {
                if ($thatMapper->exists($thatKey) && $thisMapper->exists($thisKey)) {
                    $operators[] = sprintf("%s.%s = %s.%s", $thatMapper->resource, $thatMapper->getField($thatKey), $thisMapper->resource, $thisMapper->getField($thisKey));
                }
            }
            
            if (isset($thatMapper->mapping) && \sizeof($thatMapper->restore($thatMapper->mapping))) {
                $filter = \sprintf("%s %s", $operator, (new Filter([$thatMapper, $operator]))->execute());
            }            

            return (string) \sprintf("%s %s ON %s %s", \strtoupper($join), $thatMapper->resource, \implode(\strtoupper($operator), $operators), $filter);
        }
    }

}
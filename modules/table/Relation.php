<?php

namespace Modules\Table {

    use \Component\Validator,
        \Component\Core\Adapter\Mapper;

    final class Relation extends \Component\Validation {

        public function __construct(Mapper $thisMapper, array $thatMappers, string $join = "join", string $operator = "and", array $relations = []) {
            foreach ($thatMappers as $thatMapper) {
                if ($thatMapper instanceof Mapper && !$thisMapper instanceof $thatMapper) {
                    if (isset($thisMapper->primary) && isset($thatMapper->keys) && \sizeof(($keys = \array_intersect($thisMapper->primary, $thatMapper->keys)))) {
                        $relations[] = $this->_join($thatMapper, $thisMapper, \array_intersect($thatMapper->keys, $keys), $join, $operator);
                    } elseif (isset($thatMapper->primary) && isset($thisMapper->keys) && \sizeof(($keys = \array_intersect($thatMapper->primary, $thisMapper->keys)))) {
                        $relations[] = $this->_join($thatMapper, $thisMapper, \array_flip(\array_intersect($thisMapper->keys, $keys)), $join, $operator);
                    }
                }
            }

            parent::__construct(\trim(\implode(" ", $relations)), [new Validator\IsEmpty, new Validator\IsString\Contains(["JOIN"])]);
        }

        private function _join(Mapper $thatMapper, Mapper $thisMapper, array $keys, string $join = "join", string $operator = "and", array $operators = []) {
            foreach ($keys as $thatKey => $thisKey) {
                if ($thatMapper->exists($thatKey) && $thisMapper->exists($thisKey)) {
                    $operators[] = sprintf("%s.%s = %s.%s", $thatMapper->resource, $thatMapper->getField($thatKey), $thisMapper->resource, $thisMapper->getField($thisKey));
                }
            }

            return (string) \sprintf("%s %s ON %s %s", \strtoupper($join), $thatMapper->resource, \implode(\strtoupper($operator), $operators), (($filter = new Filter([$thatMapper], \strtoupper($operator)))->isValid() ? \strtoupper($operator) . $filter->execute() : false));
        }
    }

}
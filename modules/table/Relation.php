<?php 
namespace Modules\Table {
    use \Component\Validator, \Component\Core\Adapter\Mapper;
    final class Relation extends \Component\Validation {
        public function __construct(Mapper $thisMapper, array $mappers, string $join = "JOIN", string $operator = "AND", array $relations = []) {
            foreach ($mappers as $thatMapper) {   
                if ($thatMapper instanceof Mapper && !$thisMapper instanceof $thatMapper) {
                    if (isset($thatMapper->primary) && isset($thisMapper->keys) && \sizeof(\array_intersect($thatMapper->primary, $thisMapper->keys))) {
                        $relation = new Join($thatMapper, $thisMapper, \array_intersect($thisMapper->keys, \array_intersect($thatMapper->primary, $thisMapper->keys)), $join, $operator);
                        $relations[] = $relation->execute();
                    } elseif (isset($thisMapper->primary) && isset($thatMapper->keys) && \sizeof(\array_intersect($thisMapper->primary, $thatMapper->keys))) {
                        $relation = new Join($thatMapper, $thisMapper, \array_intersect($thatMapper->keys, \array_intersect($thisMapper->primary, $thatMapper->keys)), $join, $operator);
                        $relations[] = $relation->execute();
                    }
                }
            }

            parent::__construct(\implode(\PHP_EOL, $relations), [new Validator\IsEmpty, new Validator\IsString\Contains(["JOIN"])]);
        }        
    }
}
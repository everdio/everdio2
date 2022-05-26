<?php 
namespace Modules\Table {
    use \Component\Validator, \Component\Core\Adapter\Mapper;
    final class Relation extends \Component\Validation {
        public function __construct(Mapper $thisMapper, array $mappers, string $join = "", string $operator = "AND", array $joins = []) {
            foreach ($mappers as $thatMapper) {   
                if ($thatMapper instanceof Mapper && !$thisMapper instanceof $thatMapper) {
                    if (isset($thisMapper->primary) && isset($thatMapper->keys) && \sizeof(\array_intersect($thisMapper->primary, $thatMapper->keys))) {
                        $joins[(string) $thatMapper] = $this->_join($join, $thatMapper, $thisMapper, \array_intersect($thisMapper->primary, $thatMapper->keys), $operator);
                    } elseif (isset($thatMapper->primary) && isset($thisMapper->keys) && \sizeof(\array_intersect($thatMapper->primary, $thisMapper->keys))) {
                        $joins[(string) $thisMapper] = $this->_join($join, $thisMapper, $thatMapper, \array_intersect($thatMapper->primary, $thisMapper->keys), $operator);
                    }
                }
            }
            parent::__construct(\implode(\PHP_EOL, $joins), [new Validator\IsEmpty, new Validator\IsString\Contains(["JOIN"])]);
        }        
        
        private function _join(string $join, Mapper $thatMapper, Mapper $thisMapper, array $keys, string $operator, array $operators = []) : string {
            foreach ($keys as $key) {
                $operators[] = sprintf("`%s`.`%s`.`%s`=`%s`.`%s`.`%s`", $thatMapper->database, $thatMapper->table, $thatMapper->getField($key), $thisMapper->database, $thisMapper->table, $thisMapper->getField($key));
            }
            
            $filter = new Filter([$thatMapper], $operator);
            return (string) sprintf("%s JOIN`%s`.`%s`ON%s%s", \strtoupper($join), $thatMapper->database, $thatMapper->table, \implode($operator, $operators), ($filter->isValid() ? \strtoupper($operator) . $filter->execute() : false));
        }        
    }
}
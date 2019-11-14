<?php 
namespace Modules\Table {
    use \Components\Validator;
    final class Relation extends \Components\Validation {
        public function __construct(\Modules\Table $thisTable, \Modules\Table $thatTable, string $join = null) {
            parent::__construct(sprintf("%sJOIN%sON%s", $join, $thatTable->getTable(), $this->_getRelation($thisTable, $thatTable)), [new Validator\IsString\Contains(["="])]);
        }
        
        private function _hasRelation(\Modules\Table $thisTable, \Modules\Table $thatTable) : bool {
            return (bool) (isset($thisTable->relations) && $thatTable !== $thisTable && array_search(get_class($thatTable), $thisTable->relations));
        }           
        
        private function _getRelation(\Modules\Table $thisTable, \Modules\Table $thatTable) {
            if ($this->_hasRelation($thisTable, $thatTable)) {
                return (string) sprintf("%s=%s", $thisTable->getColumn(array_search(get_class($thatTable), $thisTable->relations)), $thatTable->getColumn(array_search(get_class($thatTable), $thisTable->relations)));                            
            } 
        }
    }
}
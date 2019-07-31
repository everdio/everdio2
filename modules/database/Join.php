<?php 
namespace Modules\Database {
    use \Components\Validator;
    final class Join extends \Components\Validation {
        public function __construct(\Modules\Database $thatTable, \Modules\Database $thisTable, string $join = NULL) {
            parent::__construct(sprintf("%s JOIN %sON%s", $join, $thatTable->getTable(), $this->getRelation($thisTable, $thatTable)), [new Validator\IsString]);
        }
        
        private function hasRelation(\Modules\Database $thisTable, \Modules\Database $thatTable) : bool {
            return (bool) (isset($thisTable->relations) && array_search(get_class($thatTable), $thisTable->relations));
        }
        
        private function getRelation(\Modules\Database $thisTable, \Modules\Database $thatTable) : string {
            if ($this->hasRelation($thisTable, $thatTable)) {
                return (string) sprintf("%s=%s", $thisTable->getColumn(array_search(get_class($thatTable), $thisTable->relations)), $thatTable->getColumn(array_search(get_class($thatTable), $thisTable->relations)));
            } elseif ($this->hasRelation($thatTable, $thisTable)) {
                return (string) sprintf("%s=%s", $thisTable->getColumn(array_search(get_class($thisTable), $thatTable->relations)), $thatTable->getColumn(array_search(get_class($thisTable), $thatTable->relations)));
            } else {
                throw new Event("invalid relation between %s & %s", $thisTable->getTable(), $thatTable->getTable());
            }
        }
    }
}
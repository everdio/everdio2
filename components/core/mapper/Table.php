<?php
namespace Components\Core\Mapper {
    abstract class Table extends \Components\Core\Mapper {          
        public function getTable() : string {
            return (string) sprintf("`%s`.`%s`", $this->database, $this->table);
        }
        
        public function getColumn($parameter) : string {
            return (string) sprintf("%s.`%s`", $this->getTable(), $this->getField($parameter));
        }
        
        public function select(array $thatMappers = [], string $joins = NULL, string $where = NULL) : string {
            $select = new Table\Select(array_merge($thatMappers, array($this)));
            foreach ($thatMappers as $thatMapper) {
                if ($thatMapper instanceof Table) {
                    $join = new Table\Join($thatMapper, $this);
                    $joins .= $join->execute();
                }
            }            
            
            if ($this->isMapped()) {
                $operator = new Table\Operator($this);
                $where = "WHERE" . $operator->execute();
            }
            
            return (string) sprintf("SELECT%sFROM%s%s%s", $select->execute(), $this->getTable(), $joins, $where);
        }
        
        public function count(array $thatMappers = []) : int{
            return (int) $this->execute($this->select($thatMappers))->rowCount();
        }
        
        public function find(array $thatMappers = [], string $query = NULL) {
            if ($this->hasMapping()) {
                $this->store((array) $this->execute($this->select($thatMappers) . $query)->fetch(\PDO::FETCH_ASSOC));
            }
        }
        
        public function findAll(array $thatMappers = [], string $query = NULL) : array {
            return (array) $this->execute($this->select($thatMappers) . $query)->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        public function save() {
            $insert = new Table\Insert($this);
            $values = new Table\Values($this);
            $update = new Table\Update($this);
            
            try {
                $this->execute(sprintf("INSERT INTO%s(%s)VALUES(%s)ON DUPLICATE KEY UPDATE%s", $this->getTable(), $insert->execute(), $values->execute(), $update->execute()));    
            } catch (\Components\Event $event) {
                throw $event;
            }

            if (isset($this->keys) && !sizeof($this->restore($this->keys)) && sizeof($this->keys) === 1) {      
                $this->store(array_fill_keys($this->keys, $this->lastInsertId()));
            }
        }
        
        public function delete() {            
            if (sizeof($this->isMapped())) {
                $operator = new Table\Operator($this);
                try {
                    $this->execute(sprintf("DELETE FROM%sWHERE%s", $this->getTable(), $operator->execute()));
                    $this->reset($this->keys);
                } catch (\Components\Event $event) {
                    throw $event;
                }
            }
        }        
    }
}


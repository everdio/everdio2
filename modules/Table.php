<?php
namespace Modules {
    abstract class Table extends \Components\Core\Adapter\Mapper implements \Components\Core\Adapter\Mapper\Base {          
        final public function getTable() : string {
            return (string) sprintf("`%s`.`%s`", $this->database, $this->table);
        }
        
        final public function getColumn($parameter) : string {
            return (string) sprintf("%s.`%s`", $this->getTable(), $this->getField($parameter));
        }
        
        public function find(array $filters = [], string $query = NULL) {
            $find = new Table\Find(new Table\Select([$this]), new Table\From([$this]), array_merge($filters, [new Table\Filter($this)]));            
            $stm = $this->prepare($find->execute() . $query);
            if ($stm && $stm->execute()) {
                $this->store((array) $stm->fetch(\PDO::FETCH_ASSOC));                
            }
        }
        
        public function findAll(array $filters = [], string $query = NULL, array $records = []) : array {
            $find = new Table\Find(new Table\Select([$this]), new Table\From([$this]), array_merge($filters, [new Table\Filter($this)]));            
            $stm = $this->prepare($find->execute() . $query);
            
            if ($stm && $stm->execute()) {
                $records = (array) $stm->fetchAll(\PDO::FETCH_ASSOC);
            }
            
            return (array) $records;
        }
        
        public function save() {
            $insert = new Table\Insert($this);
            $values = new Table\Values($this);
            $update = new Table\Update($this);
            $this->query(sprintf("INSERT INTO%s(%s)VALUES(%s)ON DUPLICATE KEY UPDATE%s", $this->getTable(), $insert->execute(), $values->execute(), $update->execute()));    

            if (sizeof($this->keys) === 1 && isset($this->keys) && !sizeof($this->restore($this->keys))) {      
                $this->store(array_fill_keys($this->keys, $this->lastInsertId()));
            }
        }
        
        public function delete() {            
            if (sizeof($this->restore($this->mapping))) {
                $filter = new Table\Filter($this);
                $this->query(sprintf("DELETE FROM%sWHERE%s", $this->getTable(), $filter->execute()));
                $this->reset($this->keys);
            }
        }    
    }
}


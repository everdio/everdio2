<?php
namespace Modules\Database {
    abstract class Table extends \Components\Core\Adapter\Mapper {          
        public function getTable() : string {
            return (string) sprintf("`%s`.`%s`", $this->database, $this->table);
        }
        
        public function getColumn($parameter) : string {
            return (string) sprintf("%s.`%s`", $this->getTable(), $this->getField($parameter));
        }

        public function count(array $operators = []) : int {
            try {
                $find = new Find(new Select([$this]), new From([$this]), array_merge($operators, [new Operator($this)]));
                return (int) $this->execute($find->execute())->rowCount();
            } catch (\Components\Adapter\Event $event) {
                throw new Event(sprintf("error executing count %s", $event->getMessage()));
            }   
        }
        
        public function find(array $operators = [], string $query = NULL) {
            try {
                $find = new Find(new Select([$this]), new From([$this]), array_merge($operators, [new Operator($this)]));
                $stm = $this->prepare($find->execute() . $query);
                $stm->execute();                
                $this->store((array) $stm->fetch(\PDO::FETCH_ASSOC));                
            } catch (\Components\Adapter\Event $event) {
                throw new Event(sprintf("error executing find %s", $event->getMessage()));
            }   
        }
        
        public function findAll(array $operators = [], string $query = NULL) : array {           
            try {
                $find = new Find(new Select([$this]), new From([$this]), array_merge($operators, [new Operator($this)]));
                $stm = $this->prepare($find->execute() . $query);
                $stm->execute();
                return (array) $stm->fetchAll(\PDO::FETCH_ASSOC);
            } catch (\Components\Adapter\Event $event) {
                throw new Event(sprintf("error executing findAll %s", $event->getMessage()));
            }            
        }
        
        public function save() {
            $insert = new Insert($this);
            $values = new Values($this);
            $update = new Update($this);
            
            try {
                $this->query(sprintf("INSERT INTO%s(%s)VALUES(%s)ON DUPLICATE KEY UPDATE%s", $this->getTable(), $insert->execute(), $values->execute(), $update->execute()));    
            } catch (\Components\Adapter\Event $event) {
                throw new Event(sprintf("error save %s", $event->getMessage()));
            }  

            if (isset($this->keys) && !sizeof($this->restore($this->keys)) && sizeof($this->keys) === 1) {      
                $this->store(array_fill_keys($this->keys, $this->lastInsertId()));
            }
        }
        
        public function delete() {            
            if (sizeof($this->restore($this->mapping))) {
                $operator = new Operator($this);
                try {
                    $this->query(sprintf("DELETE FROM%sWHERE%s", $this->getTable(), $operator->execute()));
                    $this->reset($this->keys);
                } catch (\Components\Adapter\Event $event) {
                    throw new Event(sprintf("error delete %s", $event->getMessage()));
                }  
            }
        }        
    }
}


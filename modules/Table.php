<?php
namespace Modules {
    abstract class Table extends \Components\Core\Mapping {          
        public function getTable() : string {
            return (string) sprintf("`%s`.`%s`", $this->database, $this->table);
        }
        
        public function getColumn($parameter) : string {
            return (string) sprintf("%s.`%s`", $this->getTable(), $this->getField($parameter));
        }

        public function count(array $operators = []) : int {
            try {
                $find = new Table\Find(new Table\Select([$this]), new Table\From([$this]), array_merge($operators, [new Table\Operator($this)]));
                return (int) $this->execute($find->execute())->rowCount();
            } catch (\Components\Adapter\Event $event) {
                throw new Event(sprintf("error executing count %s", $event->getMessage()));
            }   
        }
        
        public function find(array $operators = [], string $query = NULL) {
            try {
                $find = new Table\Find(new Table\Select([$this]), new Table\From([$this]), array_merge($operators, [new Table\Operator($this)]));
                $this->store((array) $this->execute($find->execute() . $query)->fetch(\PDO::FETCH_ASSOC));                
            } catch (\Components\Adapter\Event $event) {
                throw new Event(sprintf("error executing find %s", $event->getMessage()));
            }   
        }
        
        public function findAll(array $operators = [], string $query = NULL) : array {           
            try {
                $find = new Table\Find(new Table\Select([$this]), new Table\From([$this]), array_merge($operators, [new Table\Operator($this)]));
                return (array) $this->execute($find->execute() . $query)->fetchAll(\PDO::FETCH_ASSOC);
            } catch (\Components\Adapter\Event $event) {
                throw new Event(sprintf("error executing findAll %s", $event->getMessage()));
            }            
        }
        
        public function save() {
            $insert = new Table\Insert($this);
            $values = new Table\Values($this);
            $update = new Table\Update($this);
            
            try {
                $this->execute(sprintf("INSERT INTO%s(%s)VALUES(%s)ON DUPLICATE KEY UPDATE%s", $this->getTable(), $insert->execute(), $values->execute(), $update->execute()));    
            } catch (\Components\Adapter\Event $event) {
                throw new Event(sprintf("error save %s", $event->getMessage()));
            }  

            if (isset($this->keys) && !sizeof($this->restore($this->keys)) && sizeof($this->keys) === 1) {      
                $this->store(array_fill_keys($this->keys, $this->lastInsertId()));
            }
        }
        
        public function delete() {            
            if (sizeof($this->restore($this->mapping))) {
                $operator = new Table\Operator($this);
                try {
                    $this->execute(sprintf("DELETE FROM%sWHERE%s", $this->getTable(), $operator->execute()));
                    $this->reset($this->keys);
                } catch (\Components\Adapter\Event $event) {
                    throw new Event(sprintf("error delete %s", $event->getMessage()));
                }  
            }
        }        
    }
}


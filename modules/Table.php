<?php
namespace Modules {
    abstract class Table extends \Components\Core\Adapter\Mapper implements \Components\Core\Adapter\Mapper\Base {          
        final public function getTable() : string {
            return (string) sprintf("`%s`.`%s`", $this->database, $this->table);
        }
        
        final public function getColumn($parameter) : string {
            return (string) sprintf("%s.`%s`", $this->getTable(), $this->getField($parameter));
        }
        
        final public function getQuery(array $tables = [], array $relations = [], array $filters = []) : string {
            foreach ($tables as $table) {
                if ($table instanceof Table) {
                    $relations[] = new Table\Relation($this, $table);
                    $filters[] = new Table\Filter($table);
                }
            }            
            
            $find = new Table\Find(new Table\Select([$this]), new Table\From([$this]), $relations, array_merge($filters, [new Table\Filter($this)]));            
            return (string) $find->execute();
        }
        
        public function find(array $tables = [], string $query = NULL,  array $records = [], array $relations = [], array $filters = []) {
            $stm = $this->prepare($this->getQuery($tables) . $query);
            if ($stm && $stm->execute()) {
                $this->store((array) $stm->fetch(\PDO::FETCH_ASSOC));                
            }
        }
        
        public function findAll(array $tables = [], string $query = NULL, array $records = [], array $relations = [], array $filters = []) : array {
            $stm = $this->prepare($this->getQuery($tables) . $query);
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

            if (isset($this->keys) && !sizeof($this->restore($this->keys)) && sizeof($this->keys) === 1) {      
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


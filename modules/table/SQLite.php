<?php

namespace Modules\Table {

    trait SQLite {

        use \Modules\Table;

        final protected function __init(): object {
            return (object) new \PDO($this->dsn);
        }
        
        final public function create(array $columns = [], array $keys = [], array $references = []): void {
            if (isset($this->mapping)) {
                foreach ($this->mapping as $column => $parameter) {
                    $validation = $this->getParameter($parameter);
                    
                    $length = ($validation->has(["length"]) ? \sprintf(" (%s)", $validation->{"\Component\Validator\Len"}->getLen()) : false);
                       
                    if ($validation->has(["string"])) {
                        $columns[$column] = \sprintf("%s VARCHAR%s", $column, $length);
                    } elseif ($validation->has(["integer", "numeric"])) {
                        $columns[$column] = \sprintf("%s INTEGER%s", $column, $length);
                    }
                }
            }
            
            
            $this->exec(\sprintf("CREATE IF NOT EXISTS %s (%s %s %s)", $this->table, $columns, $keys, $references));
        }
    }

}
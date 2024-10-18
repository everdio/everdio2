<?php

namespace Modules\Table\Model {

    use Component\Validation,
        Component\Validator;

    final class SQLite extends \Modules\Table\Model {

        use \Modules\Table\SQLite;

        public function __construct(string $table, array $_parameters = []) {
            parent::__construct([
                "table" => new Validation($table, array(new Validator\IsString))
                    ] + $_parameters);

            foreach (\array_keys($_parameters) as $parameter) {
                $this->mapping = [$parameter => $parameter];
            }
        }

        final public function setup(): void {
            $this->label = $this->beautify($this->table);
            $this->class = $this->beautify($this->table);
            $this->resource = \sprintf("%s", $this->table);
        }

        final public function create(array $create = []): void {
            foreach ($this->mapping as $parameter) {
                $validation = $this->getParameter($parameter);

                $length = ($validation->hasTypes([Validator\Len::TYPE]) ? $validation->getLen() : 0);

                $nullable = ($validation->hasTypes([Validator\IsBool::TYPE]) || (isset($this->primary) && \in_array($parameter, $this->primary)) ? false : "NOT NULL");

                if ($validation->hasTypes([Validator\IsString::TYPE])) {
                    $type = "VARCHAR";
                    if ($length > 0 && $length <= 255) {
                        $type = "VARCHAR";
                    } elseif ($length > 255) {
                        $type = "TEXT";
                    }
                } elseif ($validation->hasTypes([Validator\IsInteger::TYPE])) {
                    $type = "INTEGER";
                } elseif ($validation->Has([Validation\IsArray::TYPE])) {
                    
                }
                
                if (isset($this->primary) && \in_array($parameter, $this->primary)) {
                    if (\sizeof($this->primary) === 1) {
                        $create[] = \sprintf("%s %s PRIMARY KEY AUTOINCREMENT", $parameter, $type);
                    } else {
                        $create[] = \sprintf("%s %s PRIMARY KEY", $parameter, $type);   
                    }
                } else {
                    if ($length) {
                        $create[] = \sprintf("%s %s (%s) %s", $parameter, $type, $length, $nullable);
                    } else {
                        $create[] = \sprintf("%s %s %s", $parameter, $type, $nullable);
                    }
                }
            }
            
            if (isset($this->keys)) {
                foreach ($this->keys as $key => $foreign) {
                    if (isset($this->parents) && $this->exists($key)) {
                        if (\array_key_exists($key, $this->parents)) {
                            $create[] = \sprintf("FOREIGN KEY (%s) REFERENCES %s (%s)", $key, (new $this->parents[$key])->table, $foreign);
                        }
                    }
                }
            }
            
            try {
                $this->exec(\sprintf("CREATE TABLE IF NOT EXISTS %s (%s)", $this->table, \implode(", ", $create)));
            } catch (\PDOException $ex) {
                throw new \LogicException(\sprintf("%s: %s", $ex->getMessage(), $this->dehydrate($this->errorInfo())));
            }
            
            parent::create();
        }
    }

}
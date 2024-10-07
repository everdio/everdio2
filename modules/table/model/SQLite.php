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
            $this->resource = \sprintf("`%s`", $this->table);
        }

        final public function create(array $create = []): void {
            
            foreach ($this->mapping as $parameter) {
                $validation = $this->getParameter($parameter);
                
                $length = ($validation->has(["length"]) ? $validation->getLen() : 0);
                
                if ($validation->has(["string"])) {
                    $type = "VARCHAR";
                    if ($length > 0 && $length <= 255) {
                        $type = "VARCHAR";
                    } elseif ($length > 255) {
                        $type = "TEXT";
                    }
                    
                } elseif ($validation->has(["integer"])) {
                    $type = "INTEGER";
                }
                
                if ($length) {
                    $create[] = \sprintf("%s %s (%s)", $parameter, $type, $length);
                } else {
                    $create[] = \sprintf("%s %s", $parameter, $type);
                }
            }
            
            if (isset($this->primary)) {
                $create[] = \sprintf("PRIMARY KEY (%s)", \implode($this->primary));
            }
            
            if (isset($this->keys)) {
                foreach ($this->keys as $key) {
                    if (isset($this->parents)) {
                        if (\array_key_exists($key, $this->parents)) {
                            $create[] = \sprintf("FOREIGN KEY (%s) REFERENCES %s (%s)", $key, (new $this->parents[$key])->table, $this->keys[$key]);
                        }
                    }
                }
            }
            
            $this->exec(\sprintf("CREATE TABLE IF NOT EXISTS %s (%s)", $this->table, \implode(", ", $create)));
        }
    }

}
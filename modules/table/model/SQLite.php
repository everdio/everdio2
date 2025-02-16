<?php

namespace Modules\Table\Model {

    use Component\Validator;

    final class SQLite extends \Modules\Table\Model {

        use \Modules\Table\SQLite;

        final public function setup(array $create = []): void {
            $this->label = $this->beautify($this->table);
            $this->class = $this->beautify($this->table);
            $this->resource = \sprintf("%s", $this->table);

            foreach ($this->mapping as $column => $parameter) {
                if ($this->exists($parameter)) {
                    $validation = $this->getParameter($parameter);
                    
                    $length = ($validation->hasTypes([Validator\Len::TYPE]) ? $validation->getLen() : 0);

                    $nullable = ($validation->hasTypes([Validator\IsBool::TYPE]) ? false : "NOT NULL");
                    
                    $default = (($value = $validation->get()) ? \sprintf("DEFAULT %s", $this->dehydrate($value)) : false);
                                        
                    if ($validation->hasTypes([Validator\IsString::TYPE])) {
                        $type = "VARCHAR";
                        if ($length > 0 && $length <= 255) {
                            $type = "VARCHAR";
                        } elseif ($length > 255) {
                            $type = "TEXT";
                        }
                    } elseif ($validation->hasTypes([Validator\IsInteger::TYPE])) {
                        $type = "INTEGER";
                    } elseif ($validation->hasTypes([Validator\IsFloat::TYPE])) {
                        $type = "DECIMAL";
                    } else {
                        throw new \InvalidArgumentException(\sprintf("invalid column type for %s %s", $this->resource, $column));
                    }
                        
                    if ($type === "DECIMAL") {
                        $create[] = \sprintf("\"%s\" %s(10,5) %s %s", $column, $type, $nullable, $default);
                    } elseif ($length) {
                        $create[] = \sprintf("\"%s\" %s (%s) %s %s", $column, $type, $length, $nullable, $default);
                    } else {
                        $create[] = \sprintf("\"%s\" %s %s %s", $column, $type, $nullable, $default);
                    }                    
                }
            }

            if (isset($this->primary)) {
                $create[] = \sprintf("PRIMARY KEY (%s)", \implode(", ", \array_map(fn($primary): string => \array_search($primary, $this->mapping), $this->primary)));
            }

            if (isset($this->keys)) {
                foreach ($this->keys as $key => $foreign) {
                    if (isset($this->parents) && $this->exists($key)) {
                        if (\array_key_exists($key, $this->parents)) {
                            $create[] = \sprintf("FOREIGN KEY (\"%s\") REFERENCES \"%s\" (\"%s\")", \array_search($key, $this->mapping), \array_search($this->keys[$key], $this->mapping), (new $this->parents[$key])->table, (new $this->parents[$key])->getField($foreign));
                        }
                    }
                }
            }

            try {
                $this->exec(\sprintf("CREATE TABLE IF NOT EXISTS \"%s\" (%s)", $this->table, \implode(", ", \array_map("trim", $create))));
            } catch (\PDOException $ex) {
                throw new \LogicException(\sprintf("%s: %s", $ex->getMessage(), $this->dehydrate($this->errorInfo())));
            }
        }
    }

}
<?php

namespace Modules {

    trait Table {

        final public function statement(string $query, $stm = NULL): \PDOStatement {
            try {
                if (($stm = $this->prepare($query)) && $stm->execute()) {
                    return (object) $stm;
                }
            } catch (\ErrorException | \Exception $ex) {
                if (isset($stm)) {
                    throw new \LogicException(\sprintf("%s: %s %s", $ex->getMessage(), $this->dehydrate($stm->errorInfo()), $query));
                }

                throw new \LogicException(\sprintf("%s: %s", $ex->getMessage(), $query));
            }
        }

        final public function __set(string $parameter, $value) {
            if (!empty($value) && (!\is_array($value) && $this->getParameter($parameter)->hasTypes([\Component\Validator\IsArray::TYPE]))) {
                $value = \explode(",", $value);
            }

            return (bool) parent::__set($parameter, $value);
        }

        public function count(array $validations = [], string $query = NULL, array $parents = []): int {
            if (isset($this->parents)) {

                foreach ($this->parents as $key => $parent) {
                    $parent = new $parent;
                    $parent->reset($parent->mapping);
                    $parents[] = $parent;

                    $validations[] = new Table\Joins([new Table\Relation($this, [$parent], \strtoupper((isset($this->getParameter($key)->empty) ? "left join" : "join")))]);
                }
            }

            return (int) $this->query((new Table\Find(\array_merge([new Table\Count(\implode(", ", \array_flip(\array_intersect($this->primary, $this->mapping)))), new Table\From([$this]), new Table\Filter([$this])], $validations)))->execute() . $query)->fetchColumn();
        }

        public function find(array $validations = [], string $query = NULL): self {
            if (($row = $this->statement((new Table\Find(array_merge([new Table\Tables([$this]), new Table\From([$this]), new Table\Filter([$this])], $validations)))->execute() . $query)->fetch(\PDO::FETCH_ASSOC))) {
                $this->store($this->desanitize($row));
            }

            return (object) $this;
        }

        public function findAll(array $validations = [], array $orderby = [], int $position = 0, int $limit = 0, string $query = NULL, array $parents = []): array {
            if (isset($this->parents)) {
                foreach ($this->parents as $key => $parent) {
                    $parent = new $parent;
                    $parent->reset($parent->mapping);
                    $parents[] = $parent;

                    $validations[] = new Table\Joins([new Table\Relation($this, [$parent], \strtoupper((isset($this->getParameter($key)->empty) ? "left join" : "join")))]);
                }
            }

            if ($limit) {
                $validations[] = new Table\Limit($position, $limit);
            }

            if (\sizeof($orderby)) {
                foreach ($orderby as $order => $parameters) {
                    $validations[] = new Table\OrderBy($this, [$order => $parameters]);
                }
            } else {
                if (isset($this->primary)) {
                    $validations[] = new Table\OrderBy($this, ["desc" => $this->primary]);
                } elseif (isset($this->keys)) {
                    $validations[] = new Table\OrderBy($this, ["desc" => $this->keys]);
                }
            }

            return (array) $this->statement((new Table\Find(array_merge([new Table\From([$this]), new Table\Filter([$this]), new Table\GroupBy($this)], $validations)))->execute() . $query)->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function connect(\Component\Core\Adapter\Mapper $mapper): self {
            if (isset($mapper->primary)) {
                $this->store($mapper->restore($mapper->primary));
            }
            return (object) $this;
        }

        public function save(): self {
  
            if ($this->statement((new Table\Insert($this))->execute())) {
                $this->statement(\sprintf("%s %s", (new Table\Update($this))->execute(), (new Table\Operators([(new Table\Filter([$this]))->execute()]))->execute()))->execute();
                $this->find();
                
                if (!\array_search(false, $this->validations(\array_diff($this->mapping, $this->primary)))) {
                    return (object) $this;
                }
            }
            
            throw new \LogicException(\sprintf("failed: %s" , $this->dehydrate($this->validations(\array_diff($this->mapping, $this->primary)))));
        }

        public function delete(): self {
            if (\sizeof($this->restore($this->primary))) {
                $this->statement(\sprintf("DELETE FROM %s %s", $this->resource, (new Table\Operators([(new Table\Filter([$this]))->execute()]))->execute()));
            }
            
            return (object) $this->reset($this->mapping);
        }
    }

}


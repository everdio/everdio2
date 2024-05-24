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

                throw new \RuntimeException($ex->getMessage());
            }
        }

        final public function __set(string $parameter, $value) {
            if (!empty($value) && (!\is_array($value) && $this->getParameter($parameter)->has([\Component\Validator\IsArray::TYPE]))) {
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
                    
                    $validations[] = new Table\Joins([new Table\Relation($this, [$parent], \strtoupper((isset($this->getParameter($key)->IS_EMPTY) ? "left join" : "join")))]);
                }
            }

            $find = new Table\Find(\array_merge([new Table\Count(\implode(", ", \array_flip(\array_intersect($this->primary, $this->mapping)))), new Table\From([$this]), new Table\Filter([$this])], $validations));
            return (int) $this->query($find->execute() . $query)->fetchColumn();
        }

        public function find(array $validations = [], string $query = NULL): self {
            $find = new Table\Find(array_merge([new Table\Tables([$this]), new Table\From([$this]), new Table\Filter([$this])], $validations));
            $this->store($this->desanitize((array) $this->statement($find->execute() . $query)->fetch(\PDO::FETCH_ASSOC)));
            return (object) $this;
        }

        public function findAll(array $validations = [], array $orderby = [], int $position = 0, int $limit = 0, string $query = NULL, array $parents = []): array {
            if (isset($this->parents)) {
                foreach ($this->parents as $key => $parent) {
                    $parent = new $parent;
                    $parent->reset($parent->mapping);
                    $parents[] = $parent;
                    
                    $validations[] = new Table\Joins([new Table\Relation($this, [$parent], \strtoupper((isset($this->getParameter($key)->IS_EMPTY) ? "left join" : "join")))]);
                }
            }

            //$validations[] = new Table\Tables($parents);

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

            $find = new Table\Find(array_merge([new Table\Tables([$this]), new Table\From([$this]), new Table\Filter([$this]), new Table\GroupBy($this)], $validations));
            return (array) $this->statement($find->execute() . $query)->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function connect(\Component\Core\Adapter\Mapper $mapper): self {
            if (isset($mapper->primary)) {
                $this->store($mapper->restore($mapper->primary));
            }
            return (object) $this;
        }

        public function save(): self {
            $save = new Table\Save($this);
            if ($this->statement($save->execute())) {
                $this->find();
            }

            return (object) $this;
        }

        public function delete(): self {
            if (\sizeof($this->restore($this->mapping))) {
                $filter = new Table\Filter([$this]);
                try {
                    $this->query(\sprintf("DELETE FROM`%s`.`%s`WHERE%s", $this->database, $this->table, $filter->execute()));
                } catch (\ErrorException | \Exception $ex) {
                    throw new \LogicException(\sprintf("%s while deleting %s with %s", $ex->getMessage(), $this->table, $filter->execute()));
                }

                $this->reset($this->mapping);
            }
            return (object) $this;
        }
    }

}


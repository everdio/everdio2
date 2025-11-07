<?php

namespace Modules {

    trait Table {

        private function bind(array $validations, array $values = []): array {
            foreach ($validations as $validation) {
                if ($validation instanceof Table\Values) {
                    $values = \array_merge($values, $validation->execute());
                }
            }
            
            return (array) $values;
        }

        private function statement(string $query, array $values = [], $stm = null): \PDOStatement {            
            try {                
                if (($stm = $this->prepare($query)) && $stm->execute($values)) {
                    return (object) $stm;
                }
            } catch (\PDOException $ex) {
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

        public function count(array $validations = [], ?string $query = null): int {
            if (isset($this->parents)) {
                foreach ($this->parents as $key => $parent) {
                    $parent = new $parent;
                    $parent->reset($parent->mapping);

                    $validations[] = new Table\Joins([new Table\Relation($this, [$parent], \strtoupper((isset($this->getParameter($key)->empty) ? "left join" : "join")))]);
                }
            }

            foreach ($validations as $key => $validation) {
                if ($validation instanceof Table\Select) {
                    unset($validations[$key]);
                }
            }
    
            return (int) $this->statement((new Table\Find(\array_merge([new Table\Count, new Table\From([$this]), new Table\Filter([$this], $this->mapping)], \array_filter($validations))))->execute() . $query, $this->bind(\array_filter($validations), (new Table\Values($this, $this->mapping))->execute()))->fetchColumn();
        }

        public function find(array $validations = [], ?string $query = null): self {
            if (($row = $this->statement((new Table\Find(\array_merge([new Table\Select([$this]), new Table\From([$this]), new Table\Filter([$this], $this->mapping)], \array_filter($validations))))->execute() . $query, $this->bind(\array_filter($validations), (new Table\Values($this, $this->mapping))->execute()))->fetch(\PDO::FETCH_ASSOC))) {
                $this->store($this->desanitize($row));
            }

            return (object) $this;
        }

        public function findAll(array $validations = [], array $orderby = [], int $position = 0, int $limit = 0, ?string $query = null): array {
            if (isset($this->parents)) {
                foreach ($this->parents as $key => $parent) {
                    $parent = new $parent;
                    $parent->reset($parent->mapping);
                    
                    $validations[] = new Table\Select([$parent]);                
                    $validations[] = new Table\Joins([new Table\Relation($this, [$parent], \strtoupper((isset($this->getParameter($key)->empty) ? "left join" : "join")))]);
                }
            }
            
            if ($limit) {
                $validations[] = new Table\Limit($position, $limit);
            }

            if (\sizeof(\array_filter($orderby))) {
                foreach ($orderby as $order => $parameters) {
                    $validations[] = new Table\OrderBy($this, [$order => $parameters]);
                }
            } else {
                if (isset($this->primary)) {
                    $validations[] = new Table\OrderBy($this, ["asc" => $this->primary]);
                } elseif (isset($this->keys)) {
                    $validations[] = new Table\OrderBy($this, ["asc" => $this->keys]);
                }
            }

            return (array) $this->statement((new Table\Find(\array_merge([new Table\Select([$this]), new Table\From([$this]), new Table\Filter([$this], $this->mapping)], \array_filter($validations))))->execute() . $query, $this->bind(\array_filter($validations), (new Table\Values($this, $this->mapping))->execute()))->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function connect(\Component\Core\Adapter\Mapper $mapper): self {
            if (isset($mapper->primary)) {
                $this->store($mapper->restore($mapper->primary));
            }

            return (object) $this;
        }

        public function save(): self {
            try {
                $this->prepare((new Table\Save($this))->execute())->execute((new Table\Values($this, $this->mapping))->execute());
            } catch (\PDOException $ex) {
                throw new \LogicException(\sprintf("%s (%s)", $ex->getMessage(), (new Table\Save($this))->execute()));
            }
            
            return (object) $this->find();
        }

        public function delete(): self {
            if (\sizeof($this->restore($this->mapping))) {
                try {
                    $this->prepare(\sprintf("DELETE FROM %s %s", $this->resource, (new Table\Operators([(new Table\Filter([$this], $this->mapping))->execute()]))->execute()))->execute((new Table\Values($this, $this->mapping))->execute());
                } catch (\PDOException $ex) {
                    throw new \LogicException($ex->getMessage());
                }
            }

            return (object) $this->reset($this->mapping);
        }
    }

}
<?php

namespace Modules {

    trait Node {

        private function _prepare(array $validations = []): string {
            if (isset($this->index) && isset($this->parent)) {
                return (string) \sprintf("(%s)", $this->parent . \DIRECTORY_SEPARATOR . $this->index);
            } elseif (isset($this->parent)) {
                return (string) \sprintf("(%s)", (new Node\Filter($this->parent . \DIRECTORY_SEPARATOR . $this->tag, [new Node\Condition($this)]))->execute());
            } else {
                return (string) (new Node\Find($this->path, $validations))->execute();
            }
        }

        private function _xpath(): \DOMXPath {
            return (object) new \DOMXPath($this->getAdapter($this->unique($this->adapter)));
        }

        public function query(string $query): \DOMNodeList {
            return (object) $this->_xpath()->query($query);
        }

        public function evaluate(string $query): int {
            return (int) $this->_xpath()->evaluate("count" . $query);
        }

        public function count(array $validations = [], string $query = null): int {
            return (int) $this->evaluate($this->_prepare(\array_merge($validations, [new Node\Via($this)])) . $query);
        }

        public function find(array $validations = [], string $query = null): self {
            if (($node = $this->query($this->_prepare(\array_merge($validations, [new Node\Via($this)])) . $query)->item(0))) {
                return (object) (new Node\Map($this, $node))->execute();
            }

            return (object) $this;
        }

        public function findAll(array $validations = [], array $orderby = [], int $limit = 0, int $position = 0, string $query = null, array $records = []): array {
            foreach ($this->query($this->_prepare(\array_merge($validations, ($limit ? [new Node\Via($this, [new Node\Position($this->path, $limit, $position)])] : [new Node\Via($this)]))) . $query) as $index => $node) {
                $records[$index + 1] = (new Node\Map(new $this, $node))->execute()->restore(["index", "parent"] + $this->mapping);
            }

            if (\sizeof($orderby)) {
                foreach ($orderby as $parameter => $order) {
                    \array_multisort(\array_column($records, $parameter), $order, $records);
                }
            }

            return (array) $records;
        }

        public function connect(\Component\Core\Adapter\Mapper $mapper): self {
            if (isset($mapper->index) && isset($this->parents) && \in_array((string) $mapper, $this->parents)) {
                $this->parent = (isset($mapper->parent) ? $mapper->parent : $mapper->path) . \DIRECTORY_SEPARATOR . $mapper->index;
            }

            return (object) $this;
        }

        public function save(string|int $cdata = null): self {
            if (!$cdata && $this->exists($this->label) && isset($this->{$this->label})) {
                $cdata = $this->{$this->label};
            }

            return (object) (new Node\Map($this, (new Node\Save($this, (new Node\Create($this, $cdata))->execute()))->execute()))->execute();
        }

        public function delete(): self {
            if (isset($this->index) && isset($this->parent)) {
                if ($this->query($this->parent . \DIRECTORY_SEPARATOR . $this->index)->item(0)) {
                    $this->query($this->parent)->item(0)->removeChild($this->query($this->parent . \DIRECTORY_SEPARATOR . $this->index)->item(0));
                }
                unset($this->index);
            } elseif (isset($this->mapping)) {
                foreach ($this->findAll() as $row) {
                    $mapper = new self($row);
                    $mapper->delete();
                }
            }
            return (object) $this;
        }
    }

}
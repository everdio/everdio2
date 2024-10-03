<?php

namespace Modules {

    trait Node {

        public function prepare(array $validations = []): string {
            if (isset($this->index) && isset($this->parent)) {
                return (string) \sprintf("(%s)", $this->parent . \DIRECTORY_SEPARATOR . $this->index);
            } elseif (isset($this->parent)) {
                return (string) \sprintf("(%s)", (new Node\Filter($this->parent . \DIRECTORY_SEPARATOR . $this->tag, [new Node\Condition($this)]))->execute());
            } else {
                return (string) (new Node\Find($this->path, $validations))->execute();
            }
        }

        public function xpath(\DOMDocument $dom, ?string $namespace = null): \DOMXPath {
            $xpath = new \DOMXPath($dom);
            if ($namespace) {
                $xpath->registerNamespace("ns", $namespace);
            }
            return (object) $xpath;
        }

        public function query(string $query): \DOMNodeList {
            return (object) $this->xpath($this->getAdapter())->query($query);
        }

        public function evaluate(string $query, string $function): int|float|string {
            return $this->xpath($this->getAdapter())->evaluate($function . $query);
        }

        public function count(array $validations = [], ?string $query = null): int {
            return (int) $this->evaluate($this->prepare(\array_merge($validations, [new Node\Via($this)])) . $query, "count");
        }

        public function find(array $validations = [], ?string $query = null): self {
            if (($node = $this->query($this->prepare(\array_merge($validations, [new Node\Via($this)])) . $query)->item(0))) {
                return (object) (new Node\Map($this, $node))->execute();
            }

            return (object) $this;
        }

        public function findAll(array $validations = [], array $orderby = [], int $limit = 0, int $position = 0, ?string $query = null, array $records = []): array {
            foreach ($this->query($this->prepare(\array_merge($validations, ($limit ? [new Node\Via($this, [new Node\Position($this->path, $limit, $position)])] : [new Node\Via($this)]))) . $query) as $node) {
                $records[] = (new Node\Map(new $this, $node))->execute()->restore(["index", "parent"] + $this->mapping);
            }

            if (\sizeof($orderby)) {
                foreach ($orderby as $parameter => $order) {
                    \array_multisort(\array_column($records, $parameter), $order, $records);
                }
            }

            return (array) \array_values($records);
        }

        public function connect(\Component\Core\Adapter\Mapper $mapper): self {
            if (isset($mapper->index) && isset($this->parents) && \in_array((string) $mapper, $this->parents)) {
                $this->parent = (isset($mapper->parent) ? $mapper->parent : $mapper->path) . \DIRECTORY_SEPARATOR . $mapper->index;
            }

            return (object) $this;
        }

        public function save(string|int|null $cdata = null): self {
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
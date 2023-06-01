<?php
namespace Modules {
    trait Node {             
        private function prepare(array $validations = []) : string {
            if (isset($this->index) && isset($this->parent)) {
                return (string) \sprintf("(%s)", $this->parent . \DIRECTORY_SEPARATOR . $this->index);
            } elseif (isset($this->parent)) {
                $filter = new Node\Filter($this->parent . \DIRECTORY_SEPARATOR . $this->tag, [new Node\Condition($this)]);    
                return (string) \sprintf("(%s)", $filter->execute());
            } else {
                $find = new Node\Find($this->path, \array_merge([new Node\Filter($this->path, [new Node\Condition($this)])], $validations));    
                return (string) $find->execute();
            }
        }

        public function query(string $query) : \DOMNodeList {
            $xpath = new \DOMXPath($this->getAdapter($this->unique($this->adapter)));
            return (object) $xpath->query($query);
        }        
        
        public function evaluate(string $query) : int {
            $xpath = new \DOMXPath($this->getAdapter($this->unique($this->adapter)));
            return (int) $xpath->evaluate("count" . $query);
        }        
        
        public function count(array $validations = [], string $query = null) : int {
            return (int) $this->evaluate($this->prepare($validations) . $query);
        }        
        
        public function find(array $validations = [], string $query = null) : self {
            if (($node = $this->query($this->prepare($validations) . $query)->item(0))) {
                $map = new Node\Map($this, $node);
                return (object) $map->execute();                
            }

            return (object) $this;
        }
        
        public function findAll(array $validations = [], array $orderby = [], int $position = 0, int $limit = 0, string $query = null, array $records = []) : array {
            if ($limit) {
                $validations[] = new Node\Position($this->path, $position, $limit);
            }
            
            $mapper = new $this;
            foreach ($this->query($this->prepare($validations) . $query) as $index => $node) {
                $map = new Node\Map($mapper, $node);
                $records[$index + 1] = $map->execute()->restore(["index", "parent", $this->label] + (isset($this->mapping) ? $this->mapping : []));                
            }
            
            if (\sizeof($orderby)) {
                foreach ($orderby as $parameter => $order) { 
                    \array_multisort(\array_column($records, $parameter), $order, $records);    
                }
            }            
            
            return (array) $records;
        }     
        
        public function connect(\Component\Core\Adapter\Mapper $mapper) : self {
            if (isset($mapper->index) && isset($this->parents) && \in_array((string) $mapper, $this->parents)) {
                $this->parent = (isset($mapper->parent) ? $mapper->parent : $mapper->path) . \DIRECTORY_SEPARATOR .  $mapper->index;
            }
            
            return (object) $this;
        }
        
        public function save(string | int $cdata = null) : self {
            if (!$cdata && $this->exists($this->label) && isset($this->{$this->label})) {
                $cdata = $this->{$this->label};
            }
            
            $create = new Node\Create($this, $cdata);
            $save = new Node\Save($this, $create->execute());
            $map = new Node\Map($this, $save->execute());
            
            return (object) $map->execute();
        }
        
        public function delete() : self {
            if (isset($this->index) && isset($this->parent)) {
                if ($this->query($this->parent . \DIRECTORY_SEPARATOR . $this->index)->item(0)) {
                    $this->query($this->parent)->item(0)->removeChild($this->query($this->parent . \DIRECTORY_SEPARATOR . $this->index)->item(0));    
                }
                unset ($this->index);
            } elseif (isset($this->mapping) || isset($this->{$this->label})) {
                foreach ($this->findAll() as $row) {
                    $mapper = new $this($row);
                    $mapper->delete();
                }
            }                 
            return (object) $this;
        }
    }
}
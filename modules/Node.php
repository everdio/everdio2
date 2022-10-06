<?php
namespace Modules {
    trait Node {              
        private function prepare(array $validations = []) : string {
            if (isset($this->index) && isset($this->parent)) {
                return (string) \sprintf("(%s)", $this->parent . \DIRECTORY_SEPARATOR . $this->index);
            } elseif (isset($this->current)) {
                return (string) \sprintf("(%s)", $this->current);
            } elseif (isset($this->parent)) {
                $filter = new Node\Filter($this->parent . \DIRECTORY_SEPARATOR . $this->tag, [new Node\Condition($this)]);    
                return (string) \sprintf("(%s)", $filter->execute());
            } else {
                $find = new Node\Find($this->path, \array_merge([new Node\Filter($this->path, [new Node\Condition($this)])], $validations));    
                return (string) $find->execute();
            }
        }

        public function query(string $query) : \DOMNodeList {
            $xpath = new \DOMXPath($this->initialize());
            return (object) $xpath->query($query);
        }        
        
        public function evaluate(string $query) : int {
            $xpath = new \DOMXPath($this->initialize());
            return (int) $xpath->evaluate(sprintf("count(%s)", $query));
        }        
        
        public function count(array $validations = [], string $query = NULL) : int {
            return (int) $this->evaluate($this->prepare($validations) . $query);
        }        
        
        public function find(array $validations = [], string $query = NULL) : self {
            if (($node = $this->query($this->prepare($validations) . $query)->item(0))) {
                $map = new Node\Map($this, $node);
                return (object) $map->execute();                
            }

            return (object) $this;
        }
        
        public function findAll(array $validations = [], array $orderby = [], int $position = 0, int $limit = 0, string $query = NULL, array $records = []) : array {
            if ($limit) {
                $validations[] = new Node\Position($this->path, $position, $limit);
            }

            foreach ($this->query($this->prepare($validations) . $query) as $index => $node) { 
                $map = new Node\Map(new $this, $node);
                $mapper = $map->execute();                 
                $records[$index + 1] = $mapper->restore(["index", "current", "parent", $mapper->label] + (isset($mapper->mapping) ? $mapper->mapping : []));                
            }
            
            if (\sizeof($orderby)) {
                \array_multisort($orderby, \SORT_ASC, $records);
            }            
            
            return (array) $records;
        }     
        
        public function connect(\Component\Core\Adapter\Mapper $mapper) : self {
            if (isset($mapper->index) && isset($this->parents) && \in_array((string) $mapper, $this->parents)) {
                $this->parent = $mapper->parent . DIRECTORY_SEPARATOR . $mapper->index;
            } elseif (isset($mapper->current) && isset($this->parents) && \in_array((string) $mapper, $this->parents)) {
                $this->parent = $mapper->current;
            }
            
            return (object) $this;
        }
        
        public function save(string $cdata = NULL) : self {
            if (!$cdata && $this->exists($this->label) && isset($this->{$this->label})) {
                $cdata = $this->{$this->label};
            }
            
            $create = new Node\Create($this, $cdata);
            $save = new Node\Save($this, $create->execute());
            $map = new Node\Map($this, $save->execute());
            return (object) $map->execute();
        }
        
        public function delete() : self {
            /* using new index*/
            if (isset($this->index) && isset($this->parent)) {
                if ($this->query($this->parent . \DIRECTORY_SEPARATOR . $this->index)->item(0)) {
                    $this->query($this->parent)->item(0)->removeChild($this->query($this->parent . \DIRECTORY_SEPARATOR . $this->index)->item(0));    
                }
                
                unset ($this->index);
            }
            
            /* using old current    */
            if (isset($this->parent) && isset($this->current)) {                
                if ($this->query($this->current)->item(0)) {
                    $this->query($this->parent)->item(0)->removeChild($this->query($this->current)->item(0));    
                }
                unset ($this->current);                       
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
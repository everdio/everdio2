<?php
namespace Modules {
    trait Node {              
        private function prepare(array $validations = []) : string {
            if (isset($this->current)) {
                return (string) $this->current;
            } elseif (isset($this->parent)) {
                $filter = new Node\Filter($this->parent . \DIRECTORY_SEPARATOR . $this->tag, [new Node\Condition($this)]);    
                return (string) $filter->execute();
            } else {
                $find = new Node\Find($this->path, \array_merge([new Node\Filter($this->path, [new Node\Condition($this)])], $validations));    
                return (string) $find->execute();
            }
        }

        public function query(string $query) : \DOMNodeList {
            $xpath = new \DOMXPath($this->initialize());
            return (object) $xpath->query($query);
        }        
        
        public function count(array $validations = [], string $query = NULL) : int {
            $xpath = new \DOMXPath($this->initialize());
            return (int) $xpath->evaluate($this->prepare($validations) . $query);
        }        
        
        public function find(array $validations = [], string $query = NULL) : self {
            if (($list = $this->query($this->prepare($validations) . $query)) && ($length = $list->length - 1) >= 0) {    
                $map = new Node\Map($this, $list->item($length));
                return (object) $map->execute();
            }
            
            return (object) $this;
        }
        
        public function findAll(array $validations = [], array $orderby = [], int $position = 0, int $limit = 0, string $query = NULL, array $records = []) : array {
            if ($limit) {
                $validations[] = new Node\Position($this->path, $position, $limit);
            }
            
            if (\sizeof($orderby)) {
                
            }
            
            foreach ($this->query($this->prepare($validations) . $query) as $index => $node) { 
                $map = new Node\Map(new $this, $node);
                $mapper = $map->execute();                 
                $records[$index + 1] = $mapper->restore($mapper->keys + $mapper->primary + [$mapper->label] + (isset($mapper->mapping) ? $mapper->mapping : []));
            }
            
            return (array) \array_reverse($records);
        }     
        
        public function connect(\Component\Core\Adapter\Mapper $mapper) : self {
            if (isset($mapper->current) && isset($this->parents) && \in_array((string) $mapper, $this->parents)) {
                $this->parent = $mapper->current;
            }
            
            return (object) $this;
        }
        
        public function save(string $cdata = NULL) : self {     
            $create = new Node\Create($this, (!$cdata && $this->exists($this->label) && isset($this->{$this->label}) ? $this->{$this->label} : $cdata));
            $save = new Node\Save($this, $create->execute());
            $map = new Node\Map($this, $save->execute());
            return (object) $map->execute();
        }
        
        public function delete() : self {
            if (isset($this->parent)) {                
                if (isset($this->current) && $this->query($this->current)->item(0)) {
                    $this->query($this->parent)->item(0)->removeChild($this->query($this->current)->item(0));    
                } else {                                
                    foreach ($this->query($this->path) as $node) {
                        if ($node->parentNode->getNodePath() === $this->parent) {
                            $this->query($this->parent)->item(0)->removeChild($node);
                        }
                    }
                }                
                
                unset ($this->current);       
            } elseif (isset($this->mapping) || $this->exists($this->label)) {
                foreach ($this->findAll() as $row) {
                    $mapper = new $this($row);
                    $mapper->delete();
                }
            }                 
            
            return (object) $this;
        }
    }
}
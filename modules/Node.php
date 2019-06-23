<?php
namespace Modules {
    abstract class Node extends \Components\Core\Mapping {
        public function create(\DOMElement $node)  : string {
            $this->current = $node->getNodePath();
            $this->parent = $node->parentNode->getNodePath();                
            $this->Text = $node->textContent;

            if (isset($this->mapping)) {                
                foreach ($this->mapping as $attribute => $parameter) {
                    if ($this->exists($parameter)) {
                        $this->{$parameter} = $node->getAttribute($attribute);
                    }
                }
            }

            return (string) $this->display($node);            
        }
        
        public function xpath(array $operators = [], string $operator = "and", string $expression = "=") : string {
            $xpath = new Node\XPath($this, $operators, $operator, $expression);
            return (string) $xpath->execute();
        }
        
        public function isParent(Node $mapper) : bool {
            return (bool) (implode(DIRECTORY_SEPARATOR, array_intersect_assoc(explode(DIRECTORY_SEPARATOR, $mapper->path), explode(DIRECTORY_SEPARATOR, $this->path))) === $mapper->path);
        }
        
        public function isChild(Node $mapper) : bool {
            return (bool) (implode(DIRECTORY_SEPARATOR, array_intersect_assoc(explode(DIRECTORY_SEPARATOR, $this->path), explode(DIRECTORY_SEPARATOR, $mapper->path))) === $this->path);            
        }
        
        public function find(array $operators = [], string $query = NULL) {
            $list = $this->execute($this->xpath($operators) . $query);
            if (($length = $list->length - 1) >= 0) {    
                return (string) $this->create($list->item($length));
            }
        }
        
        public function findAll(array $operators = [], string $query = NULL) : array {
            $records = [];
            
            foreach ($this->execute($this->xpath($operators) . $query) as $index => $node) { 
                $mapper = new $this;
                $mapper->create($node);
                $records[$index + 1] = $mapper->restore(array("current", "parent") + (isset($mapper->mapping) ? $mapper->mapping : []));
            }
            
            return (array) array_reverse($records);
        }
        
        public function save() {            
            $node = (isset($this->current) ? $this->execute($this->current)->item(0) : $this->createElement($this->tag));
            
            if (isset($this->Text)) {
                $node->appendChild($this->createCDATASection($this->Text));
            }
            
            if (isset($this->mapping)) {
                foreach ($this->mapping as $attribute => $parameter) {   
                    if ($this->exists($parameter)) {
                        $node->setAttribute($attribute, $this->{$parameter});
                    }
                }
            }            
            
            if (!isset($this->current) && isset($this->parent)) {                
                $this->execute($this->parent)->item(0)->appendChild($node);
            } elseif (!isset($this->current) && !isset($this->parent) && isset($this->relations)) {
                $this->execute($this->relations["parent"]::construct()->path)->item(0)->appendChild($node);
            }
            
            return (string) $this->create($node);
        }
        
        public function delete() {
            if (isset($this->current) && isset($this->parent) && $this->execute($this->current)->item(0)) {
                $this->execute($this->parent)->item(0)->removeChild($this->execute($this->current)->item(0));
                unset ($this->current);
            }
        }
    }
}
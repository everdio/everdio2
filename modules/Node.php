<?php
namespace Modules {
    abstract class Node extends \Components\Core\Adapter\Mapper implements \Components\Core\Adapter\Mapper\Base {
        public function reverse(\DOMElement $node) : \DOMElement {
            $this->current = $node->getNodePath();          
            $this->parent = $node->parentNode->getNodePath();                
            $this->{$this->tag} = trim($node->nodeValue);
            if (isset($this->mapping)) {                
                foreach ($this->mapping as $attribute => $parameter) {
                    if ($this->exists($parameter)) {
                        $this->{$parameter} = $node->getAttribute($attribute);
                    }
                }
            }
            
            return (object) $node;
        }        
        
        public function count(array $filters = [], string $query = NULL) : int { 
            $path = new Node\Path($this->path, array_merge([new Node\Filter($this->path)], $filters));
            $xpath = new \DOMXPath($this->instance);
            return (int) $xpath->query($path->execute() . $query)->length;
        }
        
        public function find(array $filters = [], string $query = NULL) {
            $path = new Node\Path($this->path, array_merge([new Node\Filter($this->path, [new Node\Condition($this)])], $filters));
            $xpath = new \DOMXPath($this->instance);
            if (($list = $xpath->query($path->execute() . $query)) && ($length = $list->length - 1) >= 0) {    
                return (object) $this->reverse($list->item($length));
            }
        }
        
        public function findAll(array $filters = [], string $query = NULL, array $records = []) : array {
            $path = new Node\Path($this->path, array_merge([new Node\Filter($this->path, [new Node\Condition($this)])], $filters));
            $xpath = new \DOMXPath($this->instance);
            foreach ($xpath->query($path->execute() . $query) as $index => $node) { 
                $mapper = new $this;
                $mapper->reverse($node);
                $records[$index + 1] = $mapper->restore(["current", "parent", $this->tag] + (isset($mapper->mapping) ? $mapper->mapping : []));
            }
            return (array) array_reverse($records);
        }        
  
        public function save() : \DomElement {      
            $xpath = new \DOMXPath($this->instance);
            $node = (isset($this->current) ? $xpath->query($this->current)->item(0) : $this->createElement($this->tag));
            if (isset($this->{$this->tag})) {        
                $node->nodeValue = false;
                $node->appendChild($this->createCDATASection($this->{$this->tag}));
            }
            
            if (isset($this->mapping)) {
                foreach ($this->mapping as $attribute => $parameter) {   
                    if ($this->exists($parameter)) {
                        $node->setAttribute($attribute, $this->{$parameter});
                    }
                }
            }            
            
            if (!isset($this->current) && isset($this->parent)) {                        
                $xpath->query($this->parent)->item(0)->appendChild($node);
            } elseif (!isset($this->current) && !isset($this->parent) && isset($this->path)) {
                $xpath->query(implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, $this->path), 0, -1)))->item(0)->appendChild($node);
            }
            
            return (object) $this->reverse($node);
        }
        
        public function delete() {
            $xpath = new \DOMXPath($this->instance);
            if (isset($this->current) && isset($this->parent) && $xpath->query($this->current)->item(0)) {
                $xpath->query($this->parent)->item(0)->removeChild($xpath->query($this->current)->item(0));
                unset ($this->current);
            }
        }
    }
}
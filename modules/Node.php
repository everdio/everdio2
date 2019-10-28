<?php
namespace Modules {
    abstract class Node extends \Components\Core\Mapper implements \Components\Core\Mapper\Base {
        public function getXPath() : \DOMXPath {
            $index = new \Components\Index($this->instance->documentURI);
            if (!$index->exists()) {
                $index->store(new \DOMXPath($this->instance));
            }
            
            return (object) $index->restore();
        }
        
        public function getDocument(\DomElement $node) {
            $dom = new \DOMDocument;
            $dom->appendChild($dom->importNode($node, true));
            return (object) $dom;
        }
        
        public function addNode(\DOMElement $node) : \DOMElement {
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
            
            return (object) $node;
        }        
        
        public function count(array $filters = [], string $query = NULL) : int { 
            $path = new Node\Path($this->path, array_merge([new Node\Filter($this)], $filters));
            
            $xpath = new \DOMXPath($this->instance);
            return (int) $xpath->query($path->execute() . $query)->length;
        }
        
        public function find(array $filters = [], string $query = NULL) {
            $path = new Node\Path($this->path, array_merge([new Node\Filter($this)], $filters));
            $xpath = new \DOMXPath($this->instance);
            $list = $xpath->query($path->execute() . $query);
            if (($length = $list->length - 1) >= 0) {    
                return (object) $this->addNode($list->item($length));
            }
        }
        
        public function findAll(array $filters = [], string $query = NULL, array $records = []) : array {
            $path = new Node\Path($this->path, array_merge([new Node\Filter($this)], $filters));
            $xpath = new \DOMXPath($this->instance);
            foreach ($xpath->query($path->execute() . $query) as $index => $node) { 
                $mapper = new $this;
                $mapper->addNode($node);
                $records[$index + 1] = $mapper->restore(array("current", "parent") + (isset($mapper->mapping) ? $mapper->mapping : []));
            }
            
            return (array) array_reverse($records);
        }        
  
        public function save() : \DomElement {            
            $node = (isset($this->current) ? $this->getXPath()->query($this->current)->item(0) : $this->createElement($this->tag));
            
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
                $this->getXPath()->query($this->parent)->item(0)->appendChild($node);
            } elseif (!isset($this->current) && !isset($this->parent) && isset($this->path)) {
                $this->getXPath()->query(implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, $this->path), 0, -1)))->item(0)->appendChild($node);
            }
            
            return (object) $this->addNode($node);
        }
        
        public function delete() {
            if (isset($this->current) && isset($this->parent) && $this->getXPath()->query($this->current)->item(0)) {
                $this->getXPath()->query($this->parent)->item(0)->removeChild($this->getXPath()->query($this->current)->item(0));
                unset ($this->current);
            }
        }
    }
}
<?php
namespace Modules\Node {
    use \Component\Validator;
    final class Map extends \Component\Validation {
        public function __construct(\Component\Core $mapper, \DOMElement $node) {
            $mapper->current = $node->getNodePath();         
            
            if ($mapper->exists("index")) {
                $parts = \explode(\DIRECTORY_SEPARATOR, $node->getNodePath());
                $mapper->index = end($parts);
            }
            
            if (isset($node->parentNode)) {
                $mapper->parent = $node->parentNode->getNodePath();  

                if (isset($mapper->mapping)) {                
                    foreach ($mapper->mapping as $attribute => $parameter) {
                        if ($mapper->exists($parameter)) {
                            $mapper->{$parameter} = $node->getAttribute($attribute);
                        }
                    }
                }

                if ($mapper->exists($mapper->label)) {
                    $mapper->{$mapper->label} = trim($node->nodeValue);           
                }
            }
            
            parent::__construct($mapper, [new Validator\IsObject\Of("\Component\Core")]);
        }
    }
}
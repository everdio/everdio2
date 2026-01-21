<?php

namespace Modules\Node {

    use \Component\Validator;

    final class Map extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, \DOMElement $node) {
            //later: parent => Parent
            $parts = \explode(\DIRECTORY_SEPARATOR, $node->getNodePath());
            $mapper->index = \end($parts);
            
            //later: parent => Parent
            if ($mapper->hasParameter("Index")) {
                $mapper->Index = $node->getNodePath();
            }
            
            if (isset($node->parentNode)) {
                $mapper->parent = $node->parentNode->getNodePath();
                
                //later: parent => Parent
                if ($mapper->hasParameter("Parent")) {
                    $mapper->Parent = $node->parentNode->getNodePath();
                }

                foreach ($mapper->mapping as $attribute => $parameter) {
                    if ($mapper->hasParameter($parameter) && $node->hasAttribute($attribute)) {
                        $mapper->{$parameter} = \html_entity_decode($this->hydrate($node->getAttribute($attribute)), \ENT_QUOTES | \ENT_HTML5);
                    }
                }
                
                if ($mapper->hasParameter($mapper->label)) {
                    $mapper->{$mapper->label} = \html_entity_decode($node->nodeValue, \ENT_QUOTES | \ENT_HTML5);
                }
            }
            
            parent::__construct($mapper, [new Validator\IsObject]);
        }
    }

}
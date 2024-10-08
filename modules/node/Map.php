<?php

namespace Modules\Node {

    use \Component\Validator;

    final class Map extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, \DOMElement $node) {
            $parts = \explode(\DIRECTORY_SEPARATOR, $node->getNodePath());
            $mapper->index = \end($parts);

            if (isset($node->parentNode)) {
                $mapper->parent = $node->parentNode->getNodePath();

                foreach ($mapper->mapping as $attribute => $parameter) {
                    if ($mapper->exists($parameter)) {
                        $mapper->{$parameter} = \html_entity_decode($this->hydrate($node->getAttribute($attribute)), \ENT_QUOTES | \ENT_HTML5);
                    }
                }
                
                if ($mapper->exists($mapper->label)) {
                    $mapper->{$mapper->label} = \html_entity_decode($node->nodeValue, \ENT_QUOTES | \ENT_HTML5);
                }
            }

            parent::__construct($mapper, [new Validator\IsObject]);
        }
    }

}
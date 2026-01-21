<?php

namespace Modules\Node {

    use \Component\Validator;

    final class Save extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, \DOMElement $element) {
            //later: parent => Parent
            if (isset($mapper->Index)) {
                $mapper->index = $mapper->Index;
            }
            //later: parent => Parent
            if (isset($mapper->Parent)) {
                $mapper->parent = $mapper->Parent;
            }            
            
            //later: parent => Parent
            if (!isset($mapper->index)) {
                if (isset($mapper->parent) && $mapper->evaluate(\sprintf("(%s)", $mapper->parent), "count")) {
                    $mapper->query($mapper->parent)->item(0)->appendChild($element);
                } elseif (isset($mapper->path) && $mapper->evaluate(\sprintf("(%s)", $mapper->path), "count")) {
                    $mapper->query(\implode(\DIRECTORY_SEPARATOR, \array_slice(\explode(DIRECTORY_SEPARATOR, $mapper->path), 0, -1)))->item(0)->appendChild($element);
                }
            }

            parent::__construct($element, [new Validator\IsObject]);
        }
    }

}
<?php
namespace Modules\Node {
    use \Component\Validator;
    final class Save extends \Component\Validation {
        public function __construct(\Component\Core\Adapter\Mapper $mapper, \DOMElement $element) {
            if (!isset($mapper->current)) {
                if (isset($mapper->parent) && $mapper->evaluate($mapper->parent)) {
                    $mapper->query($mapper->parent)->item(0)->appendChild($element);
                } elseif (isset($mapper->path) && $mapper->evaluate($mapper->path)) {
                    $mapper->query(\implode(\DIRECTORY_SEPARATOR, \array_slice(\explode(DIRECTORY_SEPARATOR, $mapper->path), 0, -1)))->item(0)->appendChild($element);
                }
            }
            
            parent::__construct($element, [new Validator\IsObject\Of("\DOMElement")]);
        }
    }
}
<?php
namespace Modules\Node {
    use \Component\Validator;
    final class Create extends \Component\Validation {
        public function __construct(\Component\Core\Adapter\Mapper $mapper, string $cdata = NULL) {
            if (isset($mapper->parent) && isset($mapper->index)) {
                $element = $mapper->query($mapper->parent . \DIRECTORY_SEPARATOR . $mapper->index)->item(0);
            } elseif (isset($mapper->current)) {
                $element = $mapper->query($mapper->current)->item(0);
            } else {
                $element = $mapper->createElement(\strtolower($mapper->label));
            }
            
            if ($cdata) {
                $element->appendChild($mapper->createCDATASection($cdata));
            }            
            
            if (isset($mapper->mapping)) {
                foreach ($mapper->mapping as $attribute => $parameter) {  
                    if (isset($mapper->{$parameter})) {
                        $element->setAttribute($attribute, $mapper->{$parameter});
                    }
                }
            }
            
            parent::__construct($element, [new Validator\IsObject\Of("\DOMElement")]);
        }
    }
}
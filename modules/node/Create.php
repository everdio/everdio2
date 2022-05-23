<?php
namespace Modules\Node {
    use \Component\Validator;
    final class Create extends \Component\Validation {
        public function __construct(\Component\Core\Adapter\Mapper $mapper, string $cdata = NULL) {
            $element = (isset($mapper->current) ? $mapper->query($mapper->current)->item(0) : $mapper->createElement(\strtolower($mapper->label)));
            
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
<?php
namespace Modules\Node {
    use \Components\Validator;
    final class Condition extends \Components\Validation {
        public function __construct(\Modules\Node $node, string $operator = "and", string $expression = "=", $wrap = "\"%s\"", string $match = "text()=\"%s\"", array $conditions = []) {
            if (isset($node->current)) {
                parent::__construct($node->current, [new Validator\IsString\IsPath]);
            } else {
                if (isset($node->mapping) || isset($node->Text)) {
                    if (isset($node->mapping)) {
                        foreach ($node->restore($node->mapping) as $parameter => $value) {
                            if (!empty($value)) {
                                $conditions[] = sprintf("@%s %s " . $wrap, $node->getField($parameter), $expression, $value);
                            }
                        }                    
                    }
                    
                    if (isset($node->Text)) {
                        $conditions[] = sprintf($match, trim($node->Text));
                    }   
                }
                
                parent::__construct(implode(sprintf(" %s ", strtolower($operator)), $conditions), [new Validator\IsString]);
            }
        }
    }
}
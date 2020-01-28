<?php
namespace Modules\Node {
    use \Components\Validator;
    final class Condition extends \Components\Validation {
        public function __construct(\Modules\Node $node, string $operator = "and", string $expression = "=", $wrap = "\"%s\"", string $match = "text()=\"%s\"", array $conditions = []) {
            if (isset($node->mapping) || isset($node->{$node->tag})) {
                if (isset($node->mapping)) {
                    foreach ($node->restore($node->mapping) as $parameter => $value) {
                        if (!empty($value)) {
                            $conditions[] = sprintf("@%s %s " . $wrap, $node->getField($parameter), $expression, $value);
                        }
                    }                    
                }

                if (isset($node->{$node->tag})) {
                    $conditions[] = sprintf($match, trim($node->{$node->tag}));
                }   
            }

            parent::__construct(implode(sprintf(" %s ", strtolower($operator)), $conditions), [new Validator\IsString]);
        }
    }
}
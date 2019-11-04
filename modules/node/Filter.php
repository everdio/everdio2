<?php
namespace Modules\Node {
    use \Components\Validator;
    final class Filter extends \Components\Validation {
        public function __construct(\Modules\Node $node, string $operator = "and", string $expression = "=", string $match = "text()=\"%s\"", array $operators = []) {
            if (isset($node->mapping) || isset($node->Text)) {
                if (isset($node->mapping)) {
                    foreach ($node->restore($node->mapping) as $parameter => $value) {
                        if (!empty($value)) {
                            $operators[] = sprintf("@%s %s \"%s\"", $node->getField($parameter), $expression, $value);
                        }
                    }                    
                }

                if (isset($node->Text)) {
                    $operators[] = sprintf($match, trim($node->Text));
                }   
            }
            
            parent::__construct(sprintf("%s[%s]", $node->path, implode(sprintf(" %s ", strtolower($operator)), $operators)), [new Validator\IsString\Contains(["@", "contains", "text", "=", ">", "<", "!=", "and", "or"])]);
        }
    }
}
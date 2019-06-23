<?php
namespace Modules\Node {
    use \Components\Validator;
    use \Components\Validation;    
    class XOperator extends \Components\Core\Operator {
        public function __construct(\Modules\Node $node, string $operator = "and", string $expression = "=", string $match = "contains") {
            parent::__construct($node, $operator, $expression);
            $this->add("match", new Validation($match, [new Validator\IsString\InArray(["contains", "not"])]));
        }
        
        public function execute(array $operators = []) : string {
            if (isset($this->mapper->current)) {
                return (string) sprintf("[%s]", $this->mapper->current);
            } elseif (isset($this->mapper->parent)) {
                return (string) sprintf("[%s/%s]", $this->mapper->parent, $this->mapper->tag);
            } else {
                if ($this->mapper->hasMapping() || isset($this->mapper->Text)) {
                    foreach ($this->mapper->restore($this->mapper->mapping) as $parameter => $value) {
                        $operators[] = sprintf("@%s%s\"%s\"", $this->mapper->getField($parameter), $this->expression, $value);                                  
                    }                    
                }
                
                if (isset($this->mapper->Text)) {
                    $operators[] = sprintf("%s(.,\"%s\")", $this->match, trim($this->mapper->Text));
                }   
                
                return (string) sprintf("[%s]", implode(sprintf(" %s ", strtolower($this->operator)), $operators));
            }
        }
    }
}
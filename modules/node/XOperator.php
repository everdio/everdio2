<?php
namespace Modules\Node {
    use \Components\Validator;
    use \Components\Validation;    
    class XOperator extends \Components\Core\Operator {
        public function __construct(\Modules\Node $mapper, string $operator = "and", string $expression = "=", string $match = "contains") {
            parent::__construct($mapper, $operator, $expression);
            $this->add("match", new Validation($match, [new Validator\IsString\InArray(["contains", "not"])]));
        }
        
        public function execute(array $operators = []) : string {
            if ($this->mapper->hasMapping()) {
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
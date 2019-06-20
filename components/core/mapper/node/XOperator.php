<?php
namespace Components\Core\Mapper\Node {
    use \Components\Validator;
    use \Components\Validation;    
    class XOperator extends \Components\Core {
        public function __construct(\Components\Core\Mapper\Node $mapper, string $operator = "and", string $expression = "=", string $match = "contains") {
            $this->add("mapper", new Validation($mapper, [new Validator\IsObject\Of("\Components\Core\Mapper\Node")]));
            $this->add("operator", new Validation($operator, [new Validator\IsString\InArray(["and", "or"])]));
            $this->add("expression", new Validation($expression, [new Validator\IsString\InArray(["=", "!=", "<", "<=", ">", ">="])]));
            $this->add("match", new Validation($match, [new Validator\IsString\InArray(["contains", "not"])]));
        }
        
        public function execute(array $operators = []) : string {
            if ($this->mapper->hasMapping()) {
                foreach ($this->mapper->isMapped() as $parameter => $value) {
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
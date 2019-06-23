<?php
namespace Components\Core {
    use \Components\Validator;
    use \Components\Validation;    
    abstract class Operator extends \Components\Core {
        public function __construct(\Components\Core\Mapping $mapper, string $operator = "and", string $expression = "=") {
            $this->add("mapper", new Validation($mapper, [new Validator\IsObject\Of("\Components\Core\Mapping")]));
            $this->add("operator", new Validation($operator, [new Validator\IsString\InArray(["and", "or"])]));
            $this->add("expression", new Validation($expression, [new Validator\IsString\InArray(["=", "!=", "<", "<=", ">", ">="])]));
        }
        
        abstract public function execute(array $operators = []) : string;
    }
}
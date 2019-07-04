<?php
namespace Components\Core {
    use \Components\Validator;
    use \Components\Validation;    
    abstract class Operator extends \Components\Core {
        public function __construct(string $operator = "and", string $expression = "=") {
            $this->add("operator", new Validation($operator, [new Validator\IsString\InArray(["and", "or"])]));
            $this->add("expression", new Validation($expression, [new Validator\IsString\InArray(["=", "!=", "<", "<=", ">", ">="])]));
            $this->add("operators", new Validation(false, [new Validator\IsArray]));
        }

        abstract public function execute() : string;
    }
}
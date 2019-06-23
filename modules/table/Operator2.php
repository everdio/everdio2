<?php
namespace Modules\Table {
    use \Components\Validation;    
    use \Components\Validator;
    class Operator2 extends \Components\Core\Element {
        public function __construct(\Modules\Table $table, $operator = "AND", $expression = "=") {
            $this->add("table", new Validation($table, [new Validator\IsObject\Of("\Modules\Table")]));
            $this->add("operator", new Validation($operator, [new Validator\IsString]));
            $this->add("expression", new Validation($expression, [new Validator\IsString]));
        }
        
        public function execute() {
            
        }
    }
}
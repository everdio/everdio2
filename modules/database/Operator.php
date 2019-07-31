<?php
namespace Modules\Database {
    use \Components\Validator;
    use \Components\Validation;
    final class Operator extends \Components\Core\Operator { 
        public function __construct(Table $table, string $operator = "and", string $expression = "=", array $operators = []) {
            parent::__construct($operator, $expression);
            $this->add("mapper", new Validation((string) $table, [new Validator\IsString]));
            foreach ($table->restore($table->mapping) as $parameter => $value) {
                if (!isset($table->invoke($parameter)->{"Components\Validator\IsEmpty"}) && !empty($value)) {                    
                    if (isset($table->invoke($parameter)->{"Components\Validator\IsInteger"}) || isset($table->{$parameter}->{"Components\Validator\IsNumeric"})) {
                        $this->operators = [sprintf("%s%s ", $table->getColumn($parameter) . $this->expression, $value)];
                    } elseif (isset($table->invoke($parameter)->{"Components\Validator\IsString"}) || isset($table->invoke($parameter)->{"Components\Validator\IsString\InArray"})) {
                        $this->operators = [sprintf("%s'%s'", $table->getColumn($parameter) . $this->expression, $value)];
                    } elseif (isset($table->invoke($parameter)->{"Components\Validator\IsArray\Intersect"})) {                    
                        $this->operators = [sprintf(" FIND_IN_SET('%s',%s)", implode(",", $value), $table->getColumn($parameter))];
                    }            
                }
            }                
        }
        
        public function execute() : string {
            return (string) implode(strtoupper($this->operator), $this->operators);
        }
    }
}
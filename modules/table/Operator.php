<?php
namespace Modules\Table {
    use \Components\Validator;
    use \Components\Validation;
    final class Operator extends \Components\Core\Operator { 
        public function __construct(\Components\Core\Mapping $mapper, string $operator = "and", string $expression = "=", array $operators = []) {
            parent::__construct($operator, $expression);
            $this->add("mapper", new Validation((string) $mapper, [new Validator\IsString]));
            foreach ($mapper->restore($mapper->mapping) as $parameter => $value) {
                if (!isset($mapper->invoke($parameter)->{"Components\Validator\IsEmpty"}) && !empty($value)) {                    
                    if (isset($mapper->invoke($parameter)->{"Components\Validator\IsInteger"}) || isset($mapper->{$parameter}->{"Components\Validator\IsNumeric"})) {
                        $this->operators = [sprintf("%s%s ", $mapper->getColumn($parameter) . $this->expression, $value)];
                    } elseif (isset($mapper->invoke($parameter)->{"Components\Validator\IsString"}) || isset($mapper->invoke($parameter)->{"Components\Validator\IsString\InArray"})) {
                        $this->operators = [sprintf("%s'%s'", $mapper->getColumn($parameter) . $this->expression, $value)];
                    } elseif (isset($mapper->invoke($parameter)->{"Components\Validator\IsArray\Intersect"})) {                    
                        $this->operators = [sprintf(" FIND_IN_SET('%s',%s)", implode(",", $value), $mapper->getColumn($parameter))];
                    }            
                }
            }                
        }
        
        public function execute() : string {
            return (string) implode(strtoupper($this->operator), $this->operators);
        }
    }
}
<?php
namespace Components\Core\Mapper\Table {
    use \Components\Validator;
    class Operator extends \Components\Validation {
        public function __construct(\Components\Core\Mapper\Table $mapper, $operator = "AND", $expression = "=", array $values = []) {
            foreach ((isset($mapper->keys) && sizeof($mapper->restore($mapper->keys)) === sizeof($mapper->keys) ? $mapper->restore($mapper->keys) : $mapper->isMapped()) as $parameter => $value) {
                if (isset($mapper($parameter)->{"Components\Validator\IsInteger"}) || isset($mapper($parameter)->{"Components\Validator\IsNumeric"})) {
                    $values[] = sprintf("%s%s%s ", $mapper->getColumn($parameter), $expression, $value);
                } elseif (isset($mapper($parameter)->{"Components\Validator\IsString"}) || isset($mapper($parameter)->{"Components\Validator\IsString\InArray"})) {
                    $values[] = sprintf("%s%s'%s'", $mapper->getColumn($parameter), $expression, $value);
                } elseif (isset($mapper($parameter)->{"Components\Validator\IsArray\Intersect"})) {                    
                    $values[] = sprintf(" FIND_IN_SET('%s',%s)", implode(",", $value), $mapper->getColumn($parameter));
                }
            }
            
            parent::__construct(implode(strtoupper($operator), $values), array(new Validator\IsString));
        }
    }
}
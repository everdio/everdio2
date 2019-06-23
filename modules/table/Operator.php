<?php
namespace Modules\Table {
    use \Components\Validator;
    class Operator extends \Components\Validation {
        public function __construct(\Modules\Table $table, $operator = "AND", $expression = "=", array $values = []) {
            foreach ((isset($table->keys) && sizeof($table->restore($table->keys)) === sizeof($table->keys) ? $table->restore($table->keys) : array_filter($table->restore($table->mapping))) as $parameter => $value) {
                if (isset($table($parameter)->{"Components\Validator\IsInteger"}) || isset($table($parameter)->{"Components\Validator\IsNumeric"})) {
                    $values[] = sprintf("`%s`.`%s`.`%s`%s%s ", $table->database, $table->table, $table->getField($parameter), $expression, $value);
                } elseif (isset($table($parameter)->{"Components\Validator\IsString"}) || isset($table($parameter)->{"Components\Validator\IsString\InArray"})) {
                    $values[] = sprintf("`%s`.`%s`.`%s`%s'%s'", $table->database, $table->table, $table->getField($parameter), $expression, $value);
                } elseif (isset($table($parameter)->{"Components\Validator\IsArray\Intersect"})) {                    
                    $values[] = sprintf(" FIND_IN_SET('%s',`%s`.`%s`.`%s`)", implode(",", $value), $table->database, $table->table, $table->getField($parameter));
                }
            }
            
            parent::__construct(implode(strtoupper($operator), $values), array(new Validator\IsString));
        }
    }
}
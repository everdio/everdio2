<?php
namespace Modules\Table {
    use \Components\Validator;
    final class Filter extends \Components\Validation {
        public function __construct(\Modules\Table $table, string $operator = "and", string $expression = "=", array $operators = []) {
            if (isset($table->keys) && $table->isNormal($table->keys)) {
                foreach($table->restore($table->keys) as $parameter => $value) {
                    $operators[] = sprintf("%s%s ", $table->getColumn($parameter) . $expression, $value);
                }
            } elseif (isset($table->mapping) && $table->isNormal($table->mapping)) {
                foreach ($table->restore($table->mapping) as $parameter => $value) {
                    if (!empty($value) && !$table($parameter)->hasType("IS_EMPTY")) {
                        if ($table($parameter)->hasType("IS_INT") || $table($parameter)->hasType("IS_NUMERIC")) {
                            $operators[] = sprintf("%s %s ", $table->getColumn($parameter) . $expression, $value);
                        } elseif ($table($parameter)->hasType("IS_STRING") ) {
                            $operators[] = sprintf("%s '%s'", $table->getColumn($parameter) . $expression, $value);
                        } elseif ($table($parameter)->hasType("IN_ARRAY")) { 
                            $operators[] = sprintf(" FIND_IN_SET('%s',%s)", implode(",", $value), $table->getColumn($parameter));
                        }
                    }
                }               
            }
            
            parent::__construct(implode(strtoupper($operator), $operators), [new Validator\IsString]);
        }
    }
}


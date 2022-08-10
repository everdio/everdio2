<?php
namespace Modules\Table {
    use \Component\Validator;
    final class Filter extends \Component\Validation {
        public function __construct(array $tables, string $operator = "and", string $expression = "=", array $operators = [], string $additional = NULL) {
            foreach ($tables as $table) {
                //if (isset($table->mapping) && $table->isNormal($table->mapping)) {
                if (isset($table->mapping)) {
                    foreach ($table->restore($table->mapping) as $parameter => $value) {                        
                        if (!empty($value) || !isset($table->get($parameter)->IS_EMPTY)) {
                            if (\substr($table->getField($parameter), 0, 1) == '@') {
                                $additional = ":";
                                $column = $table->getField($parameter);
                            } else {
                                $additional = NULL;
                                $column = \sprintf("`%s`.`%s`.`%s`", $table->database, $table->table, $table->getField($parameter));    
                            }
                            
                            if (isset($table->get($parameter)->IS_NUMERIC)) {
                                $operators[] = \sprintf("%s %s %s ", $column, $additional . $expression, $value);
                            } elseif (isset($table->get($parameter)->IS_STRING) || isset($table->get($parameter)->IS_DEFAULT) || isset($table->get($parameter)->IS_DATE)) { 
                                $operators[] = \sprintf("%s %s '%s'", $column, $additional . $expression, $this->sanitize($value));                            
                            } elseif (isset($table->get($parameter)->IS_ARRAY)) {
                                $sets = [];
                                foreach ($value as $set) {
                                    $sets[] = \sprintf(" FIND_IN_SET('%s',%s) ", $set, $column);
                                }
                                
                                $operators[] = \implode("AND", $sets);
                            }
                        }
                    }               
                }
            }
            
            parent::__construct(\implode(\strtoupper($operator), $operators), [new Validator\IsString]);
        }
    }
}


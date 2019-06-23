<?php 
namespace Modules\Table {
    use \Components\Validator;
    class Select extends \Components\Validation {
        public function __construct(array $tables, array $values = []) {
            foreach ($tables as $table) {
                if ($table instanceof \Modules\Table) {
                    foreach ($table->mapping as $parameter) {
                        $values[$parameter] = sprintf("`%s`.`%s`.`%s` AS `%s`", $table->database, $table->table, $table->getField($parameter) , $parameter);
                    }
                }
            }
            
            parent::__construct(implode(",", $values), array(new Validator\IsString));
        }
    }
}
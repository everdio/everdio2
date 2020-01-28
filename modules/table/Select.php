<?php 
namespace Modules\Table {
    use \Components\Validator;
    final class Select extends \Components\Validation {
        public function __construct(array $tables, array $select = []) {
            foreach ($tables as $table) {               
                if ($table instanceof \Modules\Table && isset($table->mapping)) {
                    foreach ($table->mapping as $parameter) {
                        //$select[$parameter] = $table->getColumn($parameter);
                        $select[$parameter] = sprintf("%sAS`%s`", $table->getColumn($parameter), $parameter);
                    }
                }
            }                
                        
            parent::__construct(implode(",", $select), [new Validator\IsString]);
        }
    }
}
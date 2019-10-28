<?php 
namespace Modules\Table {
    use \Components\Validator;
    final class From extends \Components\Validation {
        public function __construct(array $tables, array $from = []) {
            foreach ($tables as $table) {               
                if ($table instanceof \Modules\Table) {
                    $from[] = $table->getTable();
                }
            }                
            
            parent::__construct(implode(",", $from), [new Validator\IsString]);
        }
    }
}
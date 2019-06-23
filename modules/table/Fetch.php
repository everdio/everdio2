<?php
namespace Modules\Table {
    class Fetch extends \Components\Valdiation {
        public function __construct(\Modules\Table $table, array $operators = [], string $operator = "and", string $expression = "=") {
            $select = new Select(array($table));
            foreach ($operators as $operator) {
                if ($operator instanceof Operator) {
                    
                }
            }
        }
    }
}


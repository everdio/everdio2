<?php 
namespace Modules\Table {
    final class OrderBy extends \Component\Validation {
        public function __construct(\Component\Core\Adapter\Mapper $table, array $orderby, array $values = []) {
            foreach ($orderby as $order => $parameters) {
                foreach ($table->inter($parameters) as $parameter) {
                    $values[] = \sprintf("`%s`.`%s`.`%s` %s", $table->database, $table->table, $table->getField($parameter), \strtoupper($order));
                }
            }
            
            parent::__construct("ORDER BY" . \implode(",", $values), array(new \Component\Validator\IsString\Contains(["DESC","ASC"])), self::STRICT);
        }
    }
}
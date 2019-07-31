<?php 
namespace Modules\Database {
    use \Components\Validator;
    final class OrderBy extends \Components\Validation {
        public function __construct(\Modules\Database $table, array $orderby, array $values = []) {
            foreach ($orderby as $order => $parameters) {
                foreach ($parameters as $parameter) {
                    if ($table->exists($parameter)) {
                        $values[] = sprintf("%s %s", $table->getColumn($parameter), strtoupper($order));
                    }
                }
            }
            
            parent::__construct("ORDER BY" . implode(",", $values), array(new Validator\IsString, new Validator\Len\Bigger(0)), self::STRICT);
        }
    }
}
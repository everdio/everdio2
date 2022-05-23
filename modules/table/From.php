<?php 
namespace Modules\Table {
    use \Component\Validator;
    final class From extends \Component\Validation {
        public function __construct(array $mappers, array $from = []) {
            foreach ($mappers as $mapper) {               
                if ($mapper instanceof \Component\Core) {
                    $from[] = \sprintf("`%s`.`%s`", $mapper->database, $mapper->table);
                }
            }                
            
            parent::__construct(\sprintf("FROM%s", \implode(",", $from)), [new Validator\IsString]);
        }
    }
}
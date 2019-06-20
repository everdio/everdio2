<?php 
namespace Components\Core\Mapper\Table {
    use \Components\Validator;
    class Select extends \Components\Validation {
        public function __construct(array $mappers, array $values = []) {
            foreach ($mappers as $mapper) {
                if ($mapper instanceof \Components\Core\Mapper\Table) {
                    foreach ($mapper->mapping as $parameter) {
                        $values[$parameter] = sprintf("%s AS `%s`", $mapper->getColumn($parameter), $parameter);
                    }
                }
            }
            
            parent::__construct(implode(",", $values), array(new Validator\IsString));
        }
    }
}
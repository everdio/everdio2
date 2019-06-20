<?php
namespace Components\Core\Mapper\Table {
    use \Components\Validator;
    class Insert extends \Components\Validation {
        public function __construct(\Components\Core\Mapper\Table $mapper, array $values = NULL) {
            foreach ($mapper->mapping as $parameter) {
                if (isset($mapper->{$parameter}) && !isset($mapper($parameter)->{"Components\Validator\IsDatetime\Timestamp"})) {
                    $values[$parameter] = $mapper->getColumn($parameter);
                }
            }
            
            parent::__construct(implode(",", $values), array(new Validator\IsString));
        }
    }
}
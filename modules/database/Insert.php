<?php
namespace Modules\Database {
    use \Components\Validator;
    final class Insert extends \Components\Validation {
        public function __construct(\Modules\Database $mapper, array $values = NULL) {
            foreach ($mapper->mapping as $parameter) {
                if (isset($mapper->{$parameter}) && !isset($mapper($parameter)->{"Components\Validator\IsDatetime\Timestamp"})) {
                    $values[$parameter] = $mapper->getColumn($parameter);
                }
            }
            
            parent::__construct(implode(",", $values), array(new Validator\IsString));
        }
    }
}
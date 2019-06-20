<?php
namespace Components\Core\Mapper\Table {
    use \Components\Validator;
    class Update extends \Components\Validation {
        public function __construct(\Components\Core\Mapper\Table $mapper, array $values = []) {
            foreach ($mapper->mapping as $parameter) {
                if (isset($mapper->{$parameter})) {
                    if (isset($mapper($parameter)->{"Components\Validator\IsInteger"}) || isset($mapper($parameter)->{"Components\Validator\IsNumeric"})) {
                        $values[$parameter] = sprintf("%s=%s ", $mapper->getColumn($parameter), $mapper->{$parameter});
                    } elseif (isset($mapper($parameter)->{"Components\Validator\IsString"}) || isset($mapper($parameter)->{"Components\Validator\IsString\InArray"})) {
                        $values[$parameter] = sprintf("%s='%s'",$mapper->getColumn($parameter), $mapper->{$parameter});
                    } elseif (isset($mapper($parameter)->{"Components\Validator\IsArray\Intersect"})) {
                        $values[$parameter] = sprintf("%s='%s'", $mapper->getColumn($parameter), implode(",", $mapper->{$parameter}));
                    }
                }
            }
            
            parent::__construct(implode(",", $values), array(new Validator\IsString));
        }
    }
}
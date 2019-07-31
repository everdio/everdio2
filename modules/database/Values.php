<?php
namespace Modules\Database {
    use \Components\Validator;
    final class Values extends \Components\Validation {
        public function __construct(\Modules\Database $mapper, array $values = []) {
            foreach ($mapper->mapping as $parameter) {
                if (isset($mapper->{$parameter})) {
                    if (isset($mapper($parameter)->{"Components\Validator\IsInteger"}) || isset($mapper($parameter)->{"Components\Validator\IsNumeric"})) {
                        $values[$parameter] = $mapper->{$parameter};
                    } elseif (isset($mapper($parameter)->{"Components\Validator\IsDatetime\IsDate"}) || isset($mapper($parameter)->{"Components\Validator\IsString"}) || isset($mapper($parameter)->{"Components\Validator\IsString\InArray"})) {
                        $values[$parameter] = sprintf("'%s'", $mapper->{$parameter});
                    } elseif (isset($mapper($parameter)->{"Components\Validator\IsArray\Intersect"})) {
                        $values[$parameter] = sprintf("'%s'", implode(",", $mapper->{$parameter}));
                    }
                }
            }
            
            parent::__construct(implode(",", $values), array(new Validator\IsString));
        }
    }
}
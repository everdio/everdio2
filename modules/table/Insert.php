<?php

namespace Modules\Table {

    use \Component\Validator\IsString;

    final class Insert extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $values = NULL) {
            foreach ($mapper->parameters($mapper->mapping) as $parameter => $validation) {
                if (isset($mapper->{$parameter}) && !$validation->hasTypes([IsString\IsDatetime::TYPE, IsString\IsDatetime\Timestamp::TYPE])) {
                    $values[$parameter] = $mapper->getField($parameter);
                }
            }

            parent::__construct(\implode(",", $values), array(new IsString));
        }
    }

}
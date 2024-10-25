<?php

namespace Modules\Table {

    use \Component\Validator;

    final class Update extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $mapper, array $values = []) {
            foreach ($mapper->parameters($mapper->mapping) as $parameter => $validation) {
                if (isset($mapper->{$parameter}) && !$validation->hasTypes([Validator\IsString\IsDatetime::TYPE, Validator\IsString\IsDatetime\Timestamp::TYPE])) {
                    if ($validation->hasTypes([Validator\IsEmpty::TYPE]) && empty($mapper->{$parameter}) && $mapper->{$parameter} !== 0) {
                        $values[$parameter] = \sprintf("%s = %s", $mapper->getField($parameter), "NULL");
                    } elseif ($validation->hasTypes([Validator\IsInteger::TYPE])) {
                        $values[$parameter] = \sprintf("%s = %s", $mapper->getField($parameter), $mapper->{$parameter});
                    } elseif ($validation->hasTypes([Validator\IsDefault::TYPE, Validator\IsString::TYPE, Validator\IsString::TYPE, Validator\IsString\IsDatetime\IsDate::TYPE])) {
                        $values[$parameter] = \sprintf("%s = '%s'", $mapper->getField($parameter), $this->sanitize($mapper->{$parameter}));
                    } elseif ($validation->hasTypes([Validator\IsArray::TYPE])) {
                        $values[$parameter] = \sprintf("%s = '%s'", $mapper->getField($parameter), \implode(",", $mapper->{$parameter}));
                    }
                }
            }

            parent::__construct(\sprintf("UPDATE %s SET %s", $mapper->resource, \implode(", ", $values)), [new Validator\IsString]);
        }
    }

}
<?php

namespace Modules\Table {

    use \Component\Validator;

    final class Values extends \Component\Validation {

        public function __construct(\Component\Core $table, array $values = []) {
            foreach ($table->export($table->mapping) as $parameter => $validation) {
                if (isset($table->{$parameter}) && !$validation->hasTypes([Validator\IsString\IsDatetime::TYPE, Validator\IsString\IsDatetime\Timestamp::TYPE])) {
                    if ($validation->hasTypes([Validator\IsEmpty::TYPE]) && empty($table->{$parameter}) && $table->{$parameter} !== 0) {
                        $values[$parameter] = "NULL";
                    } elseif ($validation->hasTypes([Validator\IsInteger::TYPE, Validator\IsNumeric::TYPE])) {
                        $values[$parameter] = $table->{$parameter};
                    } elseif ($validation->hasTypes([Validator\IsArray::TYPE])) {
                        $values[$parameter] = \sprintf("'%s'", \implode(",", $table->{$parameter}));
                    } elseif ($validation->hasTypes([Validator\IsNumeric::TYPE, Validator\IsString::TYPE, Validator\IsString\IsDatetime\IsDate::TYPE])) {
                        $values[$parameter] = \sprintf("'%s'", $this->sanitize($table->{$parameter}));
                    }
                }
            }


            parent::__construct(\implode(", ", $values), array(new Validator\IsDefault));
        }
    }

}
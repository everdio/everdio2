<?php

namespace Modules\Table {

    use \Component\Validator;

    final class Update extends \Component\Validation {

        public function __construct(\Component\Core $table, array $values = []) {
            foreach ($table->parameters($table->mapping) as $parameter => $validation) {
                if (isset($table->{$parameter}) && !$validation->has([Validator\IsString\IsDatetime::TYPE, Validator\IsString\IsDatetime\Timestamp::TYPE])) {
                    if ($validation->has([Validator\IsEmpty::TYPE]) && empty($table->{$parameter}) && $table->{$parameter} !== 0) {
                        $values[$parameter] = \sprintf("`%s`.`%s`.`%s`=%s ", $table->database, $table->table, $table->getField($parameter), "NULL");
                    } elseif ($validation->has([Validator\IsInteger::TYPE, Validator\IsNumeric::TYPE])) {
                        $values[$parameter] = \sprintf("`%s`.`%s`.`%s`=%s ", $table->database, $table->table, $table->getField($parameter), $table->{$parameter});
                    } elseif ($validation->has([Validator\IsDefault::TYPE, Validator\IsString::TYPE, Validator\IsString\IsDateTime\IsDate::TYPE])) {
                        $values[$parameter] = \sprintf("`%s`.`%s`.`%s`='%s'", $table->database, $table->table, $table->getField($parameter), $this->sanitize($table->{$parameter}));
                    } elseif ($validation->has([Validator\IsArray::TYPE])) {
                        $values[$parameter] = \sprintf("`%s`.`%s`.`%s`='%s'", $table->database, $table->table, $table->getField($parameter), \implode(",", $table->{$parameter}));
                    }
                }
            }

            parent::__construct(\implode(",", $values), array(new Validator\IsString));
        }
    }

}
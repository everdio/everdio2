<?php
namespace Modules\Table {
    use \Component\Validator\IsString;
    final class Insert extends \Component\Validation {
        public function __construct(\Component\Core $table, array $values = NULL) {
            foreach ($table->parameters($table->mapping) as $parameter => $validation) {
                if (isset($table->{$parameter}) && !$validation->has([IsString\IsDatetime::TYPE, IsString\IsDatetime\Timestamp::TYPE])) {
                    $values[$parameter] = \sprintf("`%s`.`%s`.`%s`", $table->database, $table->table, $table->getField($parameter));
                }
            }
            
            parent::__construct(\implode(",", $values), array(new IsString));
        }
    }
}
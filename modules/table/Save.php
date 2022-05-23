<?php
namespace Modules\Table {
    final class Save extends \Component\Validation {
        public function __construct(\Component\Core\Adapter\Mapper  $table) {
            $insert = new Insert($table);
            $values = new Values($table);
            $update = new Update($table);          
            parent::__construct(\sprintf("INSERT INTO`%s`.`%s`(%s)VALUES(%s)ON DUPLICATE KEY UPDATE%s", $table->database, $table->table, $insert->execute(), $values->execute(), $update->execute()), [new \Component\Validator\IsString]);
        }
    }
}


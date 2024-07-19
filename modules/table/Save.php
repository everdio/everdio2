<?php

namespace Modules\Table {

    final class Save extends \Component\Validation {

        public function __construct(\Component\Core\Adapter\Mapper $table) {
            parent::__construct(\sprintf("INSERT INTO`%s`.`%s`(%s)VALUES(%s)ON DUPLICATE KEY UPDATE%s", $table->database, $table->table, (new Insert($table))->execute(), (new Values($table))->execute(), (new Update($table))->execute()), [new \Component\Validator\IsString]);
        }
    }

}


<?php

namespace Modules\Table {

    trait SQLite {

        use \Modules\Table;

        final protected function addAdapter(): object {
            $pdo = new \PDO($this->dsn);
            $pdo->exec("PRAGMA foreign_keys = ON;");
            return (object) $pdo;
        }
    }

}
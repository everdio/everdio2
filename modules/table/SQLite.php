<?php

namespace Modules\Table {

    trait SQLite {

        use \Modules\Table;

        final protected function __init(): object {
            return (object) new \PDO($this->dsn);
        }
    }

}
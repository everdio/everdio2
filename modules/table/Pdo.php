<?php

namespace Modules\Table {

    trait Pdo {

        use \Modules\Table;

        final protected function __init(): object {
            return (object) new \PDO($this->dsn, $this->username, $this->password, [\PDO::ATTR_PERSISTENT => true]);
        }
    }

}
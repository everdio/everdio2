<?php

namespace Modules\Table {

    trait MySQL {

        use \Modules\Table;

        final protected function addAdapter(): object {
            return (object) new \PDO($this->dsn, $this->username, $this->password, [\PDO::ATTR_PERSISTENT => true]);
        }
    }

}
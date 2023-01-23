<?php
namespace Modules\Table {
    trait Pdo {
        use \Modules\Table;
        
        final protected function initialize() : object {
            return (object) new \PDO($this->dsn, $this->username, $this->password, [\PDO::ATTR_PERSISTENT => true]);
        }
    }
}
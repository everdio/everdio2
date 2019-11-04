<?php


$instance = new \Components\Core\Adapter\Instance($this->pdo["dsn"], new \PDO($this->pdo["dsn"], $this->pdo["username"], $this->pdo["password"]));

foreach ($this->pdo["databases"] as $database) {
    echo (string) sprintf("Library %s", sprintf($this->library["namespace"], $this->labelize($database))) . PHP_EOL;    
    ob_flush();
    
    $stm = $instance->prepare(sprintf("SHOW TABLES FROM`%s`", $database));
    $stm->execute();    
    
    foreach ($stm->fetchAll(\PDO::FETCH_COLUMN) as $table) {
        $model = new \Modules\Table\Model($this->pdo["dsn"]);
        $model->database = $database;
        $model->table = $table;
        $model->class = $this->labelize($table);
        $model->root = $this->root;
        $model->namespace = sprintf($this->library["namespace"], $this->labelize($database));
        $model->setup();

        echo (string) sprintf("Mapper %s", $model->namespace) . PHP_EOL;    
        ob_flush();                    
    }
}
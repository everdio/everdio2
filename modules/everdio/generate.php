<?php

$pdo = new \PDO($this->pdo["dsn"], $this->pdo["username"], $this->pdo["password"]);

$index = new \Components\Index($this->pdo["dsn"]);
$index->store($pdo);

foreach ($this->pdo["databases"] as $database) {
    echo (string) sprintf("Library %s", sprintf($this->library["namespace"], $this->labelize($database))) . PHP_EOL;    
    ob_flush();
    
    $stm = $pdo->prepare(sprintf("SHOW TABLES FROM`%s`", $database));
    $stm->execute();    
    
    foreach ($stm->fetchAll(\PDO::FETCH_COLUMN) as $table) {
        $model = new \Modules\Table\Model($index);
        $model->database = $database;
        $model->table = $table;
        $model->class = $this->labelize($table);
        $model->root = $this->root;
        $model->namespace = sprintf($this->library["namespace"], $this->labelize($database));
        $model->setup();

        echo (string) sprintf("Mapper %s\%s created", $model->namespace, $model->class) . PHP_EOL;    
        ob_flush();                    
    }
}
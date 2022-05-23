<?php
$pdo = new \PDO($this->pdo["dsn"], $this->pdo["username"], $this->pdo["password"]);

$stm = $pdo->prepare(\sprintf("SHOW TABLES FROM`%s`", $this->pdo["database"]));
$stm->execute();    

foreach ($stm->fetchAll(\PDO::FETCH_COLUMN) as $table) {
    $model = new \Modules\Table\Model;
    $model->store($this->pdo);
    $model->label = $this->labelize($table);
    $model->class = $this->labelize($table);
    $model->namespace = $this->model["namespace"];
    $model->table = $table;
    $model->setup();
}
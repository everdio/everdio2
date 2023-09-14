<?php

$pdo = new \PDO($this->pdo->dsn, $this->pdo->username, $this->pdo->password);

$stm = $pdo->prepare(\sprintf("SHOW TABLES FROM`%s`", $this->pdo->database));
$stm->execute();

foreach ($stm->fetchAll(\PDO::FETCH_COLUMN) as $table) {
    $model = new \Modules\Table\Model;
    $model->store($this->pdo);
    $model->store($this->model->restore());
    $model->label = $this->getLabelized($table);
    $model->class = $this->getLabelized($table);
    $model->table = $table;
    $model->setup();
}
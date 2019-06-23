<?php
use Components\Core\Library;
use Modules\Table\Model;

$pdo = new \Components\Adapter\Pdo($this->mysql["dsn"], $this->mysql["username"], $this->mysql["password"]);

foreach ($this->mysql["databases"] as $database) {
    $library = new Library($pdo);
    $library->id = $database;
    $library->root = $this->root;    
    $library->namespace = "Modules\Everdio\Library";
    $library->extend = "\Modules\Table";
    $library->create();
   
    foreach ($pdo->execute(sprintf("SHOW TABLES FROM`%s`", $database))->fetchAll(\PDO::FETCH_COLUMN) as $table) {
        $model = new Model($pdo);
        $model->database = $database;
        $model->table = $table;
        $model->root = $this->root;    
        $model->namespace = $library->getNamespace();
        $model->extend = $library->getExtend();
        $model->setup();
        $model->create();
    }
    
    echo sprintf("generated library %s", $database) . PHP_EOL;
    ob_flush();
        
}
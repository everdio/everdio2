<?php
$pdo = new \Components\Adapter\Pdo($this->mysql["dsn"], $this->mysql["username"], $this->mysql["password"]);
foreach ($this->mysql["databases"] as $database) {
    $generate = new \Modules\Everdio\Generate\Database($pdo);
    $generate->id = $database;
    $generate->root = $this->root;
    $generate->database = $database;
    $generate->setup();    
    
    echo sprintf("generated library %s", $database) . PHP_EOL;
    ob_flush();
}
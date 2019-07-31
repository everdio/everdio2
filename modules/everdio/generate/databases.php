<?php


foreach ($this->mysql["databases"] as $database) {
    $library = new Modules\Table\Library(new \Modules\Instance\Pdo($this->mysql["dsn"], $this->mysql["username"], $this->mysql["password"]), $database);
    $library->class = $this->labelize($database);
    $library->root = $this->root;
    $library->namespace = "Modules\Everdio\Library";
    $library->setup();
    
    echo sprintf("generated library %s", $database) . PHP_EOL;
    ob_flush();    
}

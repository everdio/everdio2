<?php


$constructor = new \Modules\Constructor\Dsn("\PDO", $this->dsn, $this->username, $this->password);

$database = new \Modules\Database($constructor);
$database->database = "e_cms";
$database->extends = "\Modules\Database\Table";
$database->namespace = "Modules\Everdio\Library2";
$database->path = $this->root;
$database->class = "ECms";
$database->setup();
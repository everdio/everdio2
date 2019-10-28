<?php
$index = new \Components\Index($this->pdo["dsn"]);
$index->store(new \PDO($this->pdo["dsn"], $this->pdo["username"], $this->pdo["password"]));
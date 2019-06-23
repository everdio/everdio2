<?php
use \Modules\Everdio;

foreach (Everdio\File::construct()->findAll() as $row) {
    $file = new Everdio\File($row);
    echo sprintf("%s %s", $file->File, $file->update()) . PHP_EOL;
    ob_flush();
}


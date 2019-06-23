<?php
use \Modules\Everdio;
echo "generating images" . PHP_EOL;
ob_flush();

foreach (Everdio\ImageFile::construct(array("Source" => false))->findAll() as $row) {
    try {
        $imagefile = new Everdio\ImageFile($row);
        echo $imagefile->generate() . PHP_EOL;
        ob_flush();
    } catch (\Components\Event $event) {   
        echo $event->getMessage() . PHP_EOL;
        ob_flush();
    }
}

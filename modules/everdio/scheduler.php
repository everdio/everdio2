<?php
use \Modules\Everdio\Library\EProcessing;

$pdo = new \PDO($this->pdo["dsn"], $this->pdo["username"], $this->pdo["password"]);

$index = new \Components\Index($this->pdo["dsn"]);
$index->store($pdo);

$time = time();

$scheduler = new EProcessing\Scheduler;
$scheduler->store($this->{(string) $scheduler});
if (isset($this->request)) {
    $scheduler->store($this->request);
}

$execute = new EProcessing\Execute;
$execute->store($this->{(string) $execute});

foreach ($scheduler->findAll(array($execute)) as $row) {
    $scheduler = new $scheduler($row);
    $execute = new $execute($row);
    
    if ($this->isRequested(array("now")) || isset($execute->Route) && ((in_array(date("i", $time), explode(",", $scheduler->Minute)) || empty($scheduler->Minute)) && (in_array(date("H", $time), explode(",", $scheduler->Hour)) || empty($scheduler->Hour)) && (in_array(date("d", $time), explode(",", $scheduler->Day)) || empty($scheduler->Day)) && (in_array(date("N", $time), explode(",", $scheduler->Weekday)) || empty($scheduler->Weekday)) && (in_array(date("m", $time), explode(",", $scheduler->Month)) || empty($scheduler->Month)))) {
        
        $cmd = new Components\Core\Controller\Model\Cmd(new Components\Parser\Ini);
        $cmd->root = $this->root;
        $cmd->path = $this->path;
        $cmd->request = array((string) $scheduler => $scheduler->restore($scheduler->keys));
        
        if (isset($scheduler->Arguments)) {
            $cmd->arguments = explode(DIRECTORY_SEPARATOR, $scheduler->Arguments);
        }
        
        if (isset($scheduler->Request)) {
            parse_str($scheduler->Request, $request);
            $cmd->request = $request;
        }
        
        try {
            $cmd->execute($scheduler->Path . DIRECTORY_SEPARATOR . $execute->Route);    
        } catch (\Components\Event $event) {
            echo $event->getMessage() . PHP_EOL;
        }       
    }
}
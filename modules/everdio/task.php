<?php
use \Modules\Everdio\Library\EProcessing;

$scheduler = new EProcessing\Scheduler;
if ($this->isRequested(array((string) $scheduler))) {    
    $scheduler->store($this->request[(string) $scheduler]);
    $scheduler->find();

    if (isset($scheduler->SchedulerId)) {
        $scheduler->Status = "active";
        $scheduler->save();
        
        $task = new EProcessing\Task;
        $task->SchedulerId = $scheduler->SchedulerId;        
        try {
            $task->Output = $this->sanitize(nl2br($this->execute(implode(DIRECTORY_SEPARATOR, $this->arguments)) . PHP_EOL . sprintf("%ss",  round(microtime(true) - $this->time, 3))));
            $scheduler->Status = "queued";
        } catch (\Components\Event $event) {
            $task->Output = $this->sanitize(nl2br($event->getMessage() . PHP_EOL . $event->getTraceAsString() . PHP_EOL . sprintf("%ss",  round(microtime(true) - $this->time, 3))));
            $scheduler->Status = "error";
        }

        $scheduler->save();
        $task->save();
    }
}
<?php

namespace Component\Core {

    use \Component\Validation,
        \Component\Validator,
        \Component\Caller\File\Fopen;

    abstract class Env extends \Component\Core {

        public function __construct(string $path, string $messages) {
            parent::__construct([
                "path" => new Validation(\Component\Path::construct($path)->getPath(), [new Validator\IsString\IsDir]),
                "messages" => new Validation($messages, [new Validator\IsString]),
                "time" => new Validation(\microtime(true), [new Validator\IsFloat]),
                "pid" => new Validation($this->getPid(), [new Validator\IsInteger]),
                "proc" => new Validation($this->getProc(), [new Validator\IsInteger]),
                "load" => new Validation($this->getLoad(), [new Validator\IsFloat]),
                "throttle" => new Validation(0, [new Validator\IsInteger, new Validator\IsBool]),
                "priority" => new Validation(100, [new Validator\IsInteger, new Validator\Len\Smaller(3)]),
            ]);

            $this->tick();
            $this->message(["born"]);
        }

        final protected function message(array $message): void {
            $file = new Fopen($this->path . \DIRECTORY_SEPARATOR . $this->messages, "a");
            $file->putcsv(\array_merge([$this->pid, \microtime(true)], $message));
        }

        final protected function tick(): void {
            if ($this->ping($this->pid)) {
                $file = new Fopen($this->path . \DIRECTORY_SEPARATOR . $this->pid, "w");
                $file->puts($this->time);
            }
        }
        
        final public function getCurrent(array $pids = []) : array {
            $path = new \Component\Path($this->path);
            foreach ($path as $file) {
                if ($this->ping((int) \basename($file))) {
                    $pids[] = \basename($file);
                }
            }
            
            return (array) $pids;
        }

        abstract protected function getPid(): int;

        abstract protected function getLoad(): float;

        abstract protected function getProc(): int;
        
        abstract protected function ping(int $pid): bool;

        final public function getTime() {
            return (float) \microtime(true) - $this->time;
        }

        public function __destruct() {
            $pid = new Fopen($this->path . \DIRECTORY_SEPARATOR . $this->pid, "w");
            $pid->delete();

            $this->message(["died"]);
        }
    }

}
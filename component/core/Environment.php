<?php

namespace Component\Core {

    use \Component\Validation,
        \Component\Validator,
        \Component\Caller\File\Fopen;

    abstract class Environment extends \Component\Core {

        public function __construct(string $dir, int $ttl = 0) {
            parent::__construct([
                "time" => new Validation(\microtime(true), [new Validator\IsFloat]),
                "dir" => new Validation($dir, [new Validator\IsString\IsDir]),
                "file" => new Validation(false, [new Validator\IsString\IsPath]),
                "ttl" => new Validation($ttl, [new Validator\IsInteger]),
                "pid" => new Validation($this->getPid(), [new Validator\IsInteger]),
                "proc" => new Validation($this->getProc(), [new Validator\IsInteger]),
                "load" => new Validation($this->getLoad(), [new Validator\IsFloat]),
                "priority" => new Validation(100, [new Validator\IsInteger, new Validator\Len\Smaller(3)]),
                "pool" => new Validation(false, [new Validator\IsArray])
            ]);

            $this->file = $this->touch($this->dir . \DIRECTORY_SEPARATOR . $this->pid, ["born"]);
            $this->pool = $this->pool([$this->file]);
        }

        abstract protected function getPid(): int;

        abstract protected function getLoad(): float;

        abstract protected function getProc(): int;

        abstract protected function ping(int $pid): bool;

        private function touch(string $file, array $messages): string {
            $fopen = new Fopen($file, "a");
            if (!$fopen->exists()) {
                $fopen->chmod(0770);
            }

            $fopen->putcsv(\array_merge([\microtime(true), \memory_get_peak_usage(true)], $messages));
            unset($fopen);

            return (string) $file;
        }
        
        /*
        private function pool(array $files = []): array {
            $dir = new \Component\Caller\Dir($this->dir);
            while (false !== ($file = $dir->read())) {
                if (\is_file($this->dir . \DIRECTORY_SEPARATOR . $file) && !\in_array($this->dir . \DIRECTORY_SEPARATOR . $file, $files) && (\filemtime($this->dir . \DIRECTORY_SEPARATOR . $file) + $this->ttl) > \time()) {
                    $files[] = $this->dir . \DIRECTORY_SEPARATOR . $file;
                }                
            }
            return (array) $files;
        }    
         * 
         */    

        private function pool(array $files = []): array {           
            foreach (\glob(\sprintf("%s/*", $this->dir)) as $file) {
                if (\is_file($file) && !\in_array($file, $files) && (\filemtime($file) + $this->ttl) > \time()) {
                    $files[] = $file;
                }
            }

            return (array) $files;
        }

        private function stats(string $file, array $stats = []): array {
            $fopen = new Fopen($file, "r");
            while (!$fopen->eof() && ($data = $fopen->getcsv())) {
                $stats[] = \array_combine(["time", "memory", "message"], $data);
            }

            unset($fopen);

            return (array) $stats;
        }

        private function mem(string $file): int {
            $stats = \array_column($this->stats($file), "message", "memory");
            return (int) \max(\array_keys($stats)) - \array_search("born", \array_reverse($stats));
        }

        final public function getMem(): int {
            return (int) $this->mem($this->file);
        }

        final public function getMemAlive(int $mem = 0): int {
            foreach ($this->pool as $file) {
                if ($this->ping(\basename($file))) {
                    $mem += $this->mem($file);
                }
            }

            return (int) $mem;
        }

        final public function getMemAverage(int $mem = 0): float {
            foreach ($this->pool as $file) {
                $mem += $this->mem($file);
            }

            return (float) \round($mem / \sizeof($this->pool));
        }

        private function time(string $file): float {
            $stats = \array_column($this->stats($file), "message", "time");
            return (float) \abs(\max(\array_keys($stats)) - \array_search("born", \array_reverse($stats)));
        }

        final public function getTime(): float {
            return (float) $this->time($this->file);
        }

        final public function getTimeAlive(float $time = NULL): float {
            foreach ($this->pool as $file) {
                if ($this->ping(\basename($file))) {
                    $time = \abs($time + $this->time($file));
                }
            }

            return (float) $time;
        }

        final public function getTimeAverage(float $time = NULL): float {
            foreach ($this->pool as $file) {
                $time = \abs($time + $this->time($file));
            }

            return (float) \abs($time / \sizeof($this->pool));
        }

        final public function update() {
            foreach ($this->pool as $file) {
                if ($this->ping(\basename($file))) {
                    $this->touch($file, ["alive"]);
                }
            }
        }

        public function __destruct() {
            $this->touch($this->file, ["died"]);
        }
    }

}
<?php

namespace Component\Core {

    use \Component\Validation,
        \Component\Validator,
        \Component\Caller\File\Fopen;

    abstract class Environment extends \Component\Core {

        public function __construct(string $process, string $dir, int $ttl = 0) {
            parent::__construct([
                "time" => new Validation(\microtime(true), [new Validator\IsFloat]),
                "file" => new Validation(false, [new Validator\IsString\IsPath]),
                "ttl" => new Validation($ttl, [new Validator\IsInteger]),                
                "pid" => new Validation($this->getPid(), [new Validator\IsInteger]),
                "process" => new Validation($process, [new Validator\IsString\IsFile]),                
                "load" => new Validation($this->getLoad(), [new Validator\IsFloat]),
                "priority" => new Validation(100, [new Validator\IsInteger, new Validator\Len\Smaller(3)]),
                "pool" => new Validation(false, [new Validator\IsArray])
            ]);
            
            $this->file = $this->_touch($dir . \DIRECTORY_SEPARATOR . $this->pid, ["born"]);
            $this->pool = $this->_pool($dir, [$this->file => $this->_stats($this->file)]);
        }

        abstract protected function getPid(): int;

        abstract protected function getLoad(): float;

        abstract protected function ping(int $pid): bool;

        private function _pool(string $dir, array $files = []): array {           
            foreach (\glob(\sprintf("%s/*", $dir)) as $file) {
                if (\is_file($file) && !\in_array($file, $files) && (\filemtime($file) + $this->ttl) > \time()) {
                    $files[$file] = $this->_stats($file);
                }
            }

            return (array) $files;
        }        

        private function _touch(string $file, array $messages): string {
            $fopen = new Fopen($file, "a");
            if (!$fopen->exists()) {
                $fopen->chmod(0770);
            }

            $fopen->putcsv(\array_merge([\microtime(true), $this->getLoad(), \memory_get_peak_usage(true), $this->process, $this->priority], $messages));
            unset($fopen);

            return (string) $file;
        }

        private function _stats(string $file, array $stats = []): array {
            $fopen = new Fopen($file, "r");
            while (!$fopen->eof() && ($data = $fopen->getcsv())) {
                $stats[] = \array_combine(["time", "load", "memory", "process", "priority", "message"], $data);
            }

            unset($fopen);
            
            return (array) $stats;
        }

        private function _time(string $file): float {
            $stats = \array_column($this->_stats($file), "message", "time");
            return (float) \abs(\max(\array_keys($stats)) - \array_search("born", \array_reverse($stats)));
        }        
        
        private function _mem(string $file): int {
            $stats = \array_column($this->_stats($file), "message", "memory");
            return (int) \max(\array_keys($stats)) - \array_search("born", \array_reverse($stats));
        }

        final public function getMem(): int {
            return (int) $this->_mem($this->file);
        }

        final public function getMemAlive(int $mem = 0): int {
            foreach ($this->pool as $file) {
                if ($this->ping(\basename($file))) {
                    $mem += $this->_mem($file);
                }
            }

            return (int) $mem;
        }

        final public function getMemAverage(int $mem = 0): int {
            foreach ($this->pool as $file) {
                $mem += $this->_mem($file);
            }

            return (int) \round($mem / \sizeof($this->pool));
        }


        final public function getTime(): float {
            return (float) $this->_time($this->file);
        }

        final public function getTimeAlive(float $time = NULL): float {
            foreach (\array_keys($this->pool) as $file) {
                if ($this->ping(\basename($file))) {
                    $time = \abs($time + $this->_time($file));
                }
            }

            return (float) $time;
        }

        final public function getTimeAverage(float $time = NULL): float {
            foreach (\array_keys($this->pool) as $file) {
                $time = \abs($time + $this->_time($file));
            }

            return (float) \abs($time / \sizeof($this->pool));
        }

        final public function update() {
            foreach (\array_keys($this->pool) as $file) {
                if ($this->ping(\basename($file))) {
                    $this->_touch($file, ["alive"]);
                }
            }
        }

        public function __destruct() {
            $this->_touch($this->file, ["died"]);
        }
    }

}
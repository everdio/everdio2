<?php

namespace Component\Core {

    use \Component\Validation,
        \Component\Validator,
        \Component\Caller\File\Fopen;

    abstract class Env extends \Component\Core {

        public function __construct(string $path, int $ttl = 1) {
            parent::__construct([
                "time" => new Validation(\microtime(true), [new Validator\IsFloat]),
                "path" => new Validation(\Component\Path::construct($path)->getPath(), [new Validator\IsString\IsDir]),
                "file" => new Validation(false, [new Validator\IsString\IsPath]),
                "pool" => new Validation(false, [new Validator\IsArray]),
                "ttl" => new Validation($ttl, [new Validator\IsInteger]),
                "pid" => new Validation($this->getPid(), [new Validator\IsInteger]),
                "proc" => new Validation($this->getProc(), [new Validator\IsInteger]),
                "load" => new Validation($this->getLoad(), [new Validator\IsFloat]),
                "priority" => new Validation(100, [new Validator\IsInteger, new Validator\Len\Smaller(3)]),
            ]);

            $this->file = $this->touch($this->path . \DIRECTORY_SEPARATOR . $this->pid, "born");
            $this->pool = $this->pool($this->path);
        }

        abstract protected function getPid(): int;

        abstract protected function getLoad(): float;

        abstract protected function getProc(): int;

        abstract protected function ping(int $pid): bool;

        private function touch(string $file, string $message): string {
            $fopen = new Fopen($file, "a");
            if (!$fopen->exists()) {
                $fopen->chmod(0770);
            }

            $fopen->putcsv([\microtime(true), \memory_get_peak_usage(true), $message]);
            unset ($fopen);
            
            return (string) $file;
        }

        private function pool(string $path, array $files = []): array {
            foreach (\Component\Path::construct($path) as $file) {
                if ($file->isFile() && !\in_array($file->getRealPath(), $files) && ($file->getMTime() + $this->ttl) > \time()) {
                    $files[] = $file->getRealPath();
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

        final public function memAlive(int $mem = 0): int {
            foreach ($this->pool as $file) {
                if ($this->ping(\basename($file))) {
                    $mem += $this->mem($file);
                }
            }

            return (int) $mem;
        }

        final public function memAverage(int $mem = 0): float {
            foreach ($this->pool as $file) {
                $mem += $this->mem($file);
            }

            return (float) \round($mem / \sizeof($this->pool));
        }

        private function time(string $file): float {
            $stats = \array_column($this->stats($file), "message", "time");
            return (float) \abs(\max(\array_keys($stats)) - \array_search("born", \array_reverse($stats)));
        }

        final public function timeAlive(float $time = NULL): float {
            foreach ($this->pool as $file) {
                if ($this->ping(\basename($file))) {
                    $time = \abs($time + $this->time($file));
                }
            }

            return (float) $time;
        }

        final public function timeAverage(float $time = NULL): float {
            foreach ($this->pool as $file) {
                $time = \abs($time + $this->time($file));
            }

            return (float) \abs($time / \sizeof($this->pool));
        }

        final public function update() {
            foreach ($this->pool as $file) {
                if ($this->ping(\basename($file))) {
                    $this->touch($file, "alive");
                }
            }
        }

        public function __destruct() {
            $this->touch($this->file, "died");
        }
    }

}
<?php

namespace Component\Core {

    use \Component\Validation,
        \Component\Validator;

    trait Threading {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "pool" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "pids" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                    ] + $_parameters);
        }

        final protected function build(string $callback): string {
            $model = new Thread($this->export($this->diff(["autoloader", "model"])));
            $model->callback = $callback;
            $model->thread = $thread = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . $model->unique($model->diff(), \microtime() . \rand(), "crc32") . ".php";
            $model->class = \get_class($this);
            $model->deploy();

            return (string) $thread;
        }

        final protected function command(string $thread, bool $queue = false, int $sleep = 0, int $timeout = 300, string $output = "/dev/null"): string {
            if (\str_contains(($error = \exec("php -l " . $thread)), "No syntax errors detected")) {
                if ($queue) {
                    $this->pool->{$thread} = $output = \dirname($thread) . \DIRECTORY_SEPARATOR . \basename($thread, ".php") . ".out";
                }

                return (string) \sprintf("sleep %s && timeout %s php -f %s > %s & echo $!", $sleep, $timeout, $thread, $output);
            }

            throw new \ParseError($error);
        }

        /*
         * callback executed as seperate thread at local machine
         */

        public function thread(string $callback, bool $queue = false, int $sleep = 0, int $timeout = 300) {
            $thread = $this->build($callback);

            $this->pids->{$thread} = \exec($this->command($thread, $queue, $sleep, $timeout));

            return (string) $thread;
        }

        final public function retrieve(string $thread, array $response = [], int $usleep = 10000) {
            if (isset($this->pool->{$thread})) {
                return \current($this->pool([$thread], $response, $usleep));
            }
        }
        
        final public function pool(array $threads, array $response = [], int $usleep = 10000): array {
            $pool = \array_intersect_key($this->pool->restore(), \array_flip($threads));
            
            while (\sizeof($pool)) {
                foreach ($pool as $php => $out) {
                    if (!\file_exists($php) && \file_exists($out)) {
                        
                        $response[\array_search($php, $threads)] = \file_get_contents($out);
                        \unlink($out);

                        unset($this->pool->{$php});
                        unset($this->pids->{$php});
                        
                        unset($pool[$php]);
                    }
                }

                \usleep($usleep);
            }

            return (array) $response;
        }

        /*
         * terminates any known pids if they are still running and any known output
         */

        final public function terminate($signal): void {
            foreach ($this->pids->restore() as $thread => $pid) {
                if (\posix_getpgid($pid)) {
                    \posix_kill($pid, $signal);
                }

                if (isset($this->pool->{$thread}) && \is_file($this->pool->{$thread})) {
                    \unlink($this->pool->{$thread});
                }
            }

            exit;
        }
    }

}
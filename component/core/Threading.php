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

        final protected function command(string $thread, bool $queue = false, int $timeout = 300, string $output = "/dev/null"): string {
            if (\str_contains(($error = \exec("php -l " . $thread)), "No syntax errors detected")) {
                if ($queue) {
                    $this->pool->{$thread} = $output = \dirname($thread) . \DIRECTORY_SEPARATOR . \basename($thread, ".php") . ".out";
                }

                return (string) \sprintf("timeout %s php -f %s > %s & echo $!", $timeout, $thread, $output);
            }

            throw new \ParseError($error);
        }
        

        /*
         * callback executed as seperate thread at local machine
         */

        public function thread(string $callback, bool $queue = false, int $timeout = 300) {
            $thread = $this->build($callback);

            $this->pids->{$thread} = \exec($this->command($thread, $queue, $timeout));

            return (string) $thread;
        }        

        final protected function check(string $thread, string $output) {
            if (!\file_exists($thread) && \is_file($output)) {
                $content = \file_get_contents($output);

                \unlink($output);

                unset($this->pool->{$thread});
                unset($this->pids->{$thread});

                return $content;
            }
        }

        /*
         * returning all output per thread from the pool as soon as they are all ready
         */

        public function queue(array $threads, array $response = [], int $usleep = 10000): array {
            if (\sizeof($threads)) {
                $pool = \array_intersect_key($this->pool->restore(), \array_flip($threads));

                while (\sizeof($pool)) {
                    foreach ($pool as $thread => $output) {
                        if (($content = $this->check($thread, $output))) {
                            $response[\array_search($thread, $threads)] = $content;

                            unset($pool[$thread]);
                        }

                        \usleep($usleep);
                    }
                }
            }

            return (array) \array_filter($response);
        }

        /*
         * retrieves a single thread from the queue;
         */

        final public function retrieve(string $thread) {
            if (isset($this->pool->{$thread})) {
                return \current($this->queue([$thread]));
            }
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
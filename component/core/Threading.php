<?php

namespace Component\Core {

    trait Threading {

        private $_pids = [], $_pool = [], $_servers = [];

        private function build(string $callback): string {
            $model = new Thread($this->export($this->diff(["autoloader", "model"])));
            $model->callback = $callback;
            $model->thread = $thread = $this->storage . \DIRECTORY_SEPARATOR . $model->unique($model->diff(), \microtime() . \rand(), "crc32") . ".php";
            $model->class = \get_class($this);
            $model->deploy();

            return (string) $thread;
        }
        
        private function command(string $thread, bool $queue = false, int $timeout = 300, string $output = "/dev/null") {
            if (\str_contains(($error = \exec("php -l " . $thread)), "No syntax errors detected")) {
                if ($queue) {
                    $this->_pool[$thread] = $output = \dirname($thread) . \DIRECTORY_SEPARATOR . \basename($thread, ".php") . ".out";
                }

                $this->_pids[$thread] = \exec(\sprintf("timeout %s php -f %s > %s & echo $!", $timeout, $thread, $output));
                
                return (string) $thread;
            }         
            
            throw new \ParseError($error);
        }

        /*
         * callback executed as seperate thread at local machine
         */
        final public function thread(string $callback, bool $queue = false, int $timeout = 300) {
            return (string) $this->command($this->build($callback), $queue, $timeout);
        }
        
        final public function queue(array $threads, array $response = [], int $usleep = 1000): array {
            if (\sizeof($threads)) {
                $pool = \array_intersect_key($this->_pool, \array_flip($threads));

                while (\sizeof($pool)) {
                    foreach ($pool as $thread => $output) {
                        if (!\file_exists($thread) && \is_file($output)) {
                            $response[\array_search($thread, $threads)] = \file_get_contents($output);

                            \unlink($output);

                            unset($pool[$thread]);
                            unset($this->_pool[$thread]);
                            unset($this->_pids[$thread]);
                        }

                        \usleep($usleep);
                    }
                }
            }

            return (array) \array_filter($response);
        }

        final public function retrieve(string $thread) {
            if (isset($this->_pool[$thread])) {
                return \current($this->queue([$thread]));
            }
        }

        final public function terminate($signal) {
            foreach ($this->_pids as $thread => $pid) {
                //kill the process (php)
                if (\posix_getpgid($pid)) {
                    \posix_kill($pid, $signal);
                }

                //destroy the output (out)
                if (isset($this->_pool[$thread]) && \is_file($this->_pool[$thread])) {
                    \unlink($this->_pool[$thread]);
                }
            }
        }
    }

}
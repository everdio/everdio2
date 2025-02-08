<?php

namespace Component\Core {

    trait Threading {

        protected $_pids = [], $_pool = [];

        final protected function build(string $callback): string {
            $model = new Thread($this->export($this->diff(["autoloader", "model"])));
            $model->callback = $callback;
            $model->thread = $thread = $this->storage . \DIRECTORY_SEPARATOR . $model->unique($model->diff(), \microtime() . \rand(), "crc32") . ".php";
            $model->class = \get_class($this);
            $model->deploy();

            return (string) $thread;
        }
        
        final protected function command(string $thread, bool $queue = false, int $timeout = 300, string $output = "/dev/null"): string {
            if (\str_contains(($error = \exec("php -l " . $thread)), "No syntax errors detected")) {
                if ($queue) {
                    $this->_pool[$thread] = $output = \dirname($thread) . \DIRECTORY_SEPARATOR . \basename($thread, ".php") . ".out";
                }

                return (string) \sprintf("timeout %s php -f %s > %s & echo $!", $timeout, $thread, $output);
            }         
            
            throw new \ParseError($error);
        }
        
        final protected function check(string $thread, string $output) {
            if (!\file_exists($thread) && \is_file($output)) {
                $content = \file_get_contents($output);

                \unlink($output);

                unset($this->_pool[$thread]);
                unset($this->_pids[$thread]);
                
                return $content;
            }
        }

        /*
         * callback executed as seperate thread at local machine
         */
        public function thread(string $callback, bool $queue = false, int $timeout = 300) {
            $thread = $this->build($callback);
            
            $this->_pids[$thread] = \exec($this->command($thread, $queue, $timeout));
            
            return (string) $thread;
        }
        
        
        /*
         * returning all output per thread from the pool as soon as they are all ready
         */
        public function queue(array $threads, array $response = [], int $usleep = 10000): array {
            if (\sizeof($threads)) {
                $pool = \array_intersect_key($this->_pool, \array_flip($threads));

                while (\sizeof($pool)) {
                    foreach ($pool as $thread => $output) {
                        if (($content = $this->check($thread, $output))) {
                            $response[\array_search($thread, $threads)] = $output;
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
            if (isset($this->_pool[$thread])) {
                return \current($this->queue([$thread]));
            }
        }
        
        /*
         * terminates any known pids if they are still running and any known output
         */

        final public function terminate($signal) {
            foreach ($this->_pids as $thread => $pid) {
                if (\posix_getpgid($pid)) {
                    \posix_kill($pid, $signal);
                }

                if (isset($this->_pool[$thread]) && \is_file($this->_pool[$thread])) {
                    \unlink($this->_pool[$thread]);
                }
            }
        }
    }

}
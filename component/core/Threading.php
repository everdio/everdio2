<?php

namespace Component\Core {

    trait Threading {

        private $_threads = [], $_pool = [];

        /*
         * fetching load from linux systems
         */

        final public function load(): float {
            return (float) $this->hydrate((new \Component\Caller\File\Fopen("/proc/loadavg"))->gets(5));
        }

        /*
         * calculating nicesses based on current load and cpu's
         */

        final public function niceness(float $load): int {
            return (int) \min(\max(-19, \round((($load / $this->hydrate(\exec("nproc"))) * 100) * (39 / 100) - 19)), 19);
        }

        /*
         * callback executed as seperate thread
         */

        final public function thread(string $callback, bool $queue = false, int $timeout = 300, string $output = "/dev/null") {
            $model = new Thread($this->export($this->diff(["autoloader", "model"])));
            $model->callback = $callback;
            $model->thread = $thread = $this->storage . \DIRECTORY_SEPARATOR . $model->unique($model->diff(), \microtime() . \rand(), "crc32") . ".php";
            $model->class = \get_class($this);
            $model->deploy();

            if (\str_contains(($error = \exec("php -l " . $thread)), "No syntax errors detected")) {
                if ($queue) {
                    $this->_pool[$thread] = $output = \dirname($thread) . \DIRECTORY_SEPARATOR . \basename($thread, ".php") . ".out";
                }

                $this->_threads[$thread] = \exec(\sprintf("timeout %s nice -n %s php -f %s > %s & echo $!", $timeout, $this->niceness($this->load()), $thread, $output));

                return (string) $thread;
            }

            throw new \ParseError($error);
        }

        final public function queue(array $threads, array $response = [], int $usleep = 10000): array {
            if (\sizeof($threads)) {
                $pool = \array_intersect_key($this->_pool, \array_flip($threads));

                while (\sizeof($pool)) {
                    foreach ($pool as $thread => $output) {
                        if (!\file_exists($thread) && \is_file($output)) {
                            $response[\array_search($thread, $threads)] = \file_get_contents($output);

                            \unlink($output);

                            unset($pool[$thread]);
                            unset($this->_pool[$thread]);
                            unset($this->_threads[$thread]);
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
            foreach ($this->_threads as $thread => $pid) {
                //kill the process (php)
                if (\posix_getpgid($pid)) {
                    \posix_kill($pid, $signal);
                }

                //destroy the output (out)
                if (isset($this->_pool[$thread]) && \is_file($this->_pool[$thread])) {
                    \unlink($this->_pool[$thread]);
                }
            }

            exit;
        }
    }

}
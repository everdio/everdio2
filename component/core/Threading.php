<?php

namespace Component\Core {

    trait Threading {

        /*
         * fetching load from linux systems
         */

        final public function load(): float {
            $fopen = new \Component\Caller\File\Fopen("/proc/loadavg");
            return (float) $this->hydrate($fopen->gets(5));
        }

        /*
         * calculating nicesses based on current load and cpu's
         */

        final public function niceness(): int {
            return (int) \min(\max(-19, \round((($this->load() / $this->hydrate(\exec("nproc"))) * 100) * (39 / 100) - 19)), 19);
        }
        
        final public function thread(string $callback, bool $queue = false, int|float $sleep = 0, int $timeout = 300, string $output = "/dev/null") {
            $model = new Thread;
            $model->import($this->parameters($this->diff(["autoloader", "model", "threads", "pool"])));
            $model->callback = $callback;
            $model->thread = $thread = $this->storage . \DIRECTORY_SEPARATOR . $model->unique($model->diff(), \microtime() . \rand(), "crc32") . ".php";
            $model->class = \get_class($this);
            unset($model);

            if (\str_contains(($check = \exec("php -l " . $thread)), "No syntax errors detected")) {
                if ($queue) {
                    $this->pool->{$thread} = $output = \dirname($thread) . \DIRECTORY_SEPARATOR . \basename($thread, ".php") . ".out";
                }

                $this->threads->{$thread} = \exec(\sprintf("sleep %s; timeout %s nice -n %s php -f %s > %s & echo $!", $sleep, $timeout, $this->niceness(), $thread, $output));

                return (string) $thread;
            } else {
                \unlink($thread);
                
                throw new \ParseError($check);
            }
        }

        final public function terminate($signal) {
            foreach ($this->threads->restore() as $thread => $pid) {
                //kill the process (php)
                if (\posix_getpgid($pid)) {
                    \posix_kill($pid, $signal);
                }

                //destroy the output (out)
                if (isset($this->pool->{$thread}) && \is_file($this->pool->{$thread})) {
                    \unlink($this->pool->{$thread});
                }
            }

            exit;
        }

        final public function queue(array $threads, array $response = [], int $usleep = 10000): array {
            if (\sizeof($threads)) {
                $pool = \array_intersect_key($this->pool->restore(), \array_flip($threads));

                while (\sizeof($pool)) {
                    foreach ($pool as $thread => $output) {
                        if (!\file_exists($thread) && \is_file($output)) {
                            $response[\array_search($thread, $threads)] = \file_get_contents($output);

                            \unlink($output);

                            unset($pool[$thread]);
                            unset($this->pool->{$thread});
                            unset($this->threads->{$thread});
                        }

                        \usleep($usleep);
                    }
                }
            }

            return (array) \array_filter($response);
        }

        final public function retrieve(string $thread) {
            if (isset($this->pool->{$thread})) {
                return \current($this->queue([$thread]));
            }
        }
    }

}
<?php

namespace Component\Core\Adapter {

    trait Threading {
        /*
         * checks the syntax by executing the php thread in test mode, returns the error or false
         */

        abstract public function syntax(string $thread);

        /*
         * kills any threads from pids
         */

        abstract public function kill($pid, $signal): void;

        /*
         * executes the php shell command with some settings, must return the PID
         */

        abstract public function exec(string $thread, int $sleep = 0, int $timeout = 300, bool|string $output = false): int;

        /*
         * creates the PHP thread to be executed and checks the syntax and
         * callback executed as seperate thread at local machine
         */

        final public function thread(string $callback, bool $queue = false, int $sleep = 0, int $timeout = 300, bool|string $output = false): string {
            $model = new Thread($this->export($this->diff(["autoloader", "model"])));
            $model->callback = $callback;
            $model->thread = $thread = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . $model->unique($model->diff(), \microtime() . \rand(), "crc32") . ".php";
            $model->class = \get_class($this);
            $model->deploy();

            if (!($error = $this->syntax($thread))) {

                if ($queue) {
                    $output = $this->pool->{$thread} = \dirname($thread) . \DIRECTORY_SEPARATOR . \basename($thread, ".php") . ".out";
                }

                $this->pids->{$thread} = $this->exec($thread, $sleep, $timeout, $output);

                return $thread;
            }

            throw new \ParseError(\sprintf("PARSE_ERROR %s (%s)", $error, $thread));
        }

        /*
         * retrieves a single response from the pool
         */

        final public function retrieve(string $thread, array $response = [], int $usleep = 10000) {
            if (isset($this->pool->{$thread})) {
                return \current($this->pool([$thread], $response, $usleep));
            }
        }

        /*
         * retrieves an array of responses based on an array of threads
         */

        final public function pool(array $threads, array $response = [], int $usleep = 1000): array {
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
                $this->kill($pid, $signal);

                if (isset($this->pool->{$thread}) && \is_file($this->pool->{$thread})) {
                    \unlink($this->pool->{$thread});
                }
            }

            exit;
        }
    }

}
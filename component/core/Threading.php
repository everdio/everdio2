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

        final protected function create(string $callback): string {
            $model = new Thread($this->export($this->diff(["autoloader", "model"])));
            $model->callback = $callback;
            $model->thread = $thread = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . $model->unique($model->diff(), \microtime() . \rand(), "crc32") . ".php";
            $model->class = \get_class($this);
            $model->deploy();
            
            if (!\str_contains(($error = $this->run("php -l " . $thread)), "No syntax errors detected")) {
                throw new \ParseError(\sprintf("%s while executing %s", $error, $thread));
            }
            
            return (string) $thread;
        }

        protected function run(string $command): mixed {
            return \exec($command);
        }

        /*
         * callback executed as seperate thread at local machine
         */

        public function thread(string $callback, bool $queue = false, int $sleep = 0, int $timeout = 300, string $output = "/dev/null"): string {
            $thread = $this->create($callback);
            
            if ($queue) {
                $this->pool->{$thread} = $output = \dirname($thread) . \DIRECTORY_SEPARATOR . \basename($thread, ".php") . ".out";
            }                

            $this->pids->{$thread} = $this->run(\sprintf("sleep %s && timeout %s php -f %s > %s & echo $!", $sleep, $timeout, $thread, $output));

            return $thread;
        }

        final public function retrieve(string $thread, array $response = [], int $usleep = 10000) {
            if (isset($this->pool->{$thread})) {
                return \current($this->pool([$thread], $response, $usleep));
            }
        }

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
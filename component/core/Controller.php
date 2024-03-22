<?php

namespace Component\Core {

    use \Component\Validation,
        \Component\Validator;

    abstract class Controller extends \Component\Core {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "time" => new Validation(false, [new Validator\IsFloat, new Validator\IsInteger]),
                "path" => new Validation(false, [new Validator\IsString\IsDir]),
                "basename" => new Validation(false, [new Validator\IsString]),
                "debug" => new Validation(false, [new Validator\IsString]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "arguments" => new Validation(false, [new Validator\IsString, new Validator\IsString\IsPath]),
                "storage" => new Validation(false, [new Validator\IsString, new Validator\IsString\IsDir]),
                "threads" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "queue" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),                
                "reserved" => new Validation(false, [new Validator\IsArray])
                    ] + $_parameters);

            $this->reserved = $this->diff();
        }

        /*
         * dispatching the Cojtroller if exists!
         */

        public function dispatch(string $path): string {
            return (string) $this->getController($path);
        }
        
        /*
         * checks if controller php file exists
         */

        final public function hasController(string $path): bool {
            return (bool) \is_file($this->path . \DIRECTORY_SEPARATOR . $path . ".php");
        }

        /*
         * including php file and catching it's output for returning
         */

        final public function getController(string $path) {
            if ($this->hasController($path)) {
                \ob_start();

                require $this->path . \DIRECTORY_SEPARATOR . $path . ".php";

                return (string) \ob_get_clean();
            }
        }

        /*
         * processing callbacks from output {{string}}
         */

        final public function getCallbacks(string $output, array $matches = []): string {
            if (\is_string($output) && \preg_match_all("!\{\{(.+?)\}\}!", $output, $matches, \PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $key => $match) {
                    try {
                        if (!\is_string(($data = $this->callback($match)))) {
                            $data = \str_replace("false", "", $this->dehydrate($data));
                        }
                    } catch (\BadMethodCallException $ex) {
                        throw new \RuntimeException(\sprintf("bad method call %s in %s", $ex->getMessage(), $match));
                    } catch (\BadFunctionCallException $ex) {
                        throw new \RuntimeException(\sprintf("bad function call %s in %s", $ex->getMessage(), $match));
                    }

                    $output = \str_replace($matches[0][$key], $data, $output);
                }
            }

            return (string) $output;
        }

        /*
         * process timer using the server global request_time_float
         */

        final public function getTiming(): float {
            return (float) (\microtime(true) - $this->time);
        }

        /*
         * fetching load from linux systems
         */

        final public function getLoad(): float {
            $fopen = new \Component\Caller\File\Fopen("/proc/loadavg");
            return (float) $this->hydrate($fopen->gets(5));
        }
        
        /*
         * fetching # of cpu's from linux system
         */
        
        final public function getCPUs(): int {
            return (int) $this->hydrate(\exec("nproc"));
        }
        
        /*
         * calculating nicesses based on current load and cpu's
         */
        final public function getNiceness() : int {
            return (int) \min(\max(-19, \round((($this->getLoad() / $this->getCPUs()) * 100) * (39 / 100) - 19)), 19);
        }             

        /*
         * Creating a thread model to execute concurrently (threaded), calculating nicesess based on load (1 core is max 0.50, factor = 2)
         */

        final public function thread(string $callback, bool $queue = false, int|float $sleep = 0, int $timeout = 300, string $output = "/dev/null") {
            $model = new Thread;
            $model->import($this->parameters($this->diff(["threads", "queue"])));
            $model->callback = $callback;
            $model->thread = $thread = $this->storage . \DIRECTORY_SEPARATOR . $model->unique($model->diff(), \microtime() . \rand(), "crc32") . ".php";
            $model->class = \get_class($this);
            unset($model);

            if (\str_contains(\exec("php -l " . $thread), "No syntax errors detected")) {
                if ($queue) {
                    $this->queue->{$thread} = $output = \dirname($thread) . \DIRECTORY_SEPARATOR . \basename($thread, ".php") . ".out";
                }
            
                $this->threads->{$thread} = \exec(\sprintf("sleep %s; timeout %s nice -n %s php -f %s > %s & echo $!", $sleep, $timeout, $this->getNiceness(), $thread, $output));
                
                return (string) $thread;
            } elseif (isset($this->debug) && isset($this->request->{$this->debug})) {
                throw new \ParseError($thread);
            }
        }

        final public function retrieve(string $thread) {
            if (isset($this->queue->{$thread})) {
                return \current($this->queue([$thread]));
            }
        }
        
        final public function terminate($signal) {
            foreach ($this->threads->restore() as $thread => $pid) {
                if (\posix_getpgid($pid)) {
                    \posix_kill($pid, $signal);
                }

                if (isset($this->queue->{$thread}) && \is_file($this->queue->{$thread})) {
                    \unlink ($this->queue->{$thread});
                }
            }
            
            exit;
        }        

        final public function queue(array $threads, array $response = [], int $usleep = 10000): array {
            if (\sizeof($threads)) {
                $pool = \array_intersect_key($this->queue->restore(), \array_flip($threads));

                while (\sizeof($pool)) {
                    foreach ($pool as $thread => $output) {
                        if (!\file_exists($thread) && \is_file($output)) {
                            $response[\array_search($thread, $threads)] = \file_get_contents($output);
                            \unlink($output);

                            unset($this->queue->{$thread});
                            unset($this->threads->{$thread});
                            unset($pool[$thread]);
                        }

                        \usleep($usleep);
                    }
                }
            }
            
            return (array) \array_filter($response);            
        }

        /*
         * executing this controller by dispatching a path and setting that path as a new reference for dispatches
         */

        final public function execute(string $path, array $request = []) {
            $controller = new $this;
            $controller->import($this->parameters($this->reserved));
            $controller->request->store($request);
            $controller->path = \realpath($this->path . \DIRECTORY_SEPARATOR . \dirname($path));
            $controller->basename = \basename($path);
            
            if (isset($controller->path)) {
                try {
                    return $controller->getCallbacks($controller->dispatch($this->basename));
                } catch (\UnexpectedValueException $ex) {
                    throw new \RuntimeException(\sprintf("%s: invalid parameter value %s in %s(%s)", \get_class($ex), $ex->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                } catch (\InvalidArgumentException $ex) {
                    throw new \RuntimeException(\sprintf("%s: parameter %s required in %s(%s)", \get_class($ex), $ex->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                } catch (\ValueError | \ErrorException $ex) {
                    throw new \RuntimeException(\sprintf("%s (%s) in %s(%s)", \get_class($ex), $ex->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                } 
            }
        }

        public function __clone() {
            $controller = parent::__clone();
            $controller->import($this->parameters($this->reserved));
            return (object) $controller;
        }
    }

}
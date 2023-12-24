<?php

namespace Component\Core {

    use \Component\Validation,
        \Component\Validator;

    abstract class Controller extends \Component\Core {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "path" => new Validation(false, [new Validator\IsString\IsDir]),
                "debug" => new Validation(false, [new Validator\IsString]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "arguments" => new Validation(false, [new Validator\IsString, new Validator\IsString\IsPath]),
                "pool" => new Validation(false, [new Validator\IsString, new Validator\IsString\IsDir]),
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
         * processing any existing callbacks from output string
         */

        final public function getCallbacks(string $output, array $matches = []): string {
            if (\is_string($output) && \preg_match_all("!\{\{(.+?)\}\}!", $output, $matches, \PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $key => $match) {
                    try {
                        if (!\is_string(($data = $this->callback($match)))) {
                            $data = \str_replace("false", "", $this->dehydrate($data));
                        }
                    } catch (\BadMethodCallException $ex) {
                        throw new \LogicException(\sprintf("bad method call %s in %s", $ex->getMessage(), $match));
                    } catch (\BadFunctionCallException $ex) {
                        throw new \LogicException(\sprintf("bad function call %s in %s", $ex->getMessage(), $match));
                    }

                    $output = \str_replace($matches[0][$key], $data, $output);
                }
            }

            return (string) $output;
        }

        final public function thread(string $callback, bool $queue = false, array $_parameters = [], int|float $sleep = 0, string $output = "/dev/null"): string {
            $model = new Thread($_parameters);
            $model->import($this->parameters($this->diff()));
            $model->callback = $callback;
            $model->thread = $thread = $this->pool . \DIRECTORY_SEPARATOR . \crc32($callback) . ".php";
            $model->class = \get_class($this);
            unset($model);

            if ($queue && !isset($this->queue->{$thread})) {
                $this->queue->{$thread} = $output = \dirname($thread) . \DIRECTORY_SEPARATOR . \basename($thread, ".php") . ".out";
            }

            \exec(\sprintf("sleep %s; nice -n %s php -f %s > %s &", $sleep, 0, $thread, $output));

            return (string) $thread;
        }
      
        final public function queue(array $pool, array $output = [], int $usleep = 1000): array {
            $threads = \array_intersect_key($this->queue->restore(), \array_flip($pool));

            while (\sizeof($threads)) {
                foreach ($threads as $thread => $file) {
                    if (!\file_exists($thread) && \is_file($file)) {
                        $output[] = \file_get_contents($file);
                        \unlink($file);
                        unset($threads[$thread]);
                    }

                    \usleep($usleep);
                }
            }
            

            return (array) \array_filter($output);
        }

        /*
         * executing this controller by dispatching a path and setting that path as a new reference for dispatches
         */

        final public function execute(string $path, array $values = []) {
            $controller = new $this;
            $controller->reset($controller->reserved);
            $controller->store($this->restore($this->reserved) + $values);
            $controller->path = \realpath($this->path . \DIRECTORY_SEPARATOR . \dirname($path));

            if (isset($controller->path)) {
                try {
                    return $controller->getCallbacks($controller->dispatch(\basename($path)));
                } catch (\UnexpectedValueException $ex) {
                    throw new \LogicException(\sprintf("invalid parameter value %s in %s(%s)", $ex->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                } catch (\InvalidArgumentException $ex) {
                    throw new \LogicException(\sprintf("parameter %s required in %s(%s)", $ex->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                } catch (\ErrorException | \TypeError | \ParseError | \Error $ex) {
                    throw new \LogicException(\sprintf("%s in %s (%s)", $ex->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                }
            }
        }
    }

}
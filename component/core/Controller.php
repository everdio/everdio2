<?php

namespace Component\Core {

    use \Component\Validation,
        \Component\Validator,
        \Component\Caller\File\Fopen;

    abstract class Controller extends \Component\Core {

        public function __construct(string $socket, array $_parameters = []) {
            parent::__construct([
                "_time" => new Validation(\microtime(true), [new Validator\IsFloat]),
                "_socket" => new Validation($socket, [new Validator\IsString\IsPath]),
                "_token" => new Validation(\bin2hex(\openssl_random_pseudo_bytes(32)), [new Validator\IsString, new Validator\Len\Bigger(45)]),
                "_reserved" => new Validation(false, [new Validator\IsArray]),
                "pid" => new Validation(\posix_getpid(), [new Validator\IsInteger]),
                "path" => new Validation(false, [new Validator\IsString\IsPath\IsReal]),
                "debug" => new Validation(false, [new Validator\IsString]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "arguments" => new Validation(false, [new Validator\IsString, new Validator\IsString\IsPath])
                    ] + $_parameters);

            $this->_reserved = $this->diff();
            
            if (!file_exists($this->_socket . \DIRECTORY_SEPARATOR . $this->pid)) {
                \file_put_contents($this->_socket . \DIRECTORY_SEPARATOR . $this->pid, $this->_time);
                $this->update(["created"]);
            }
        }

        final protected function update(array $message): void {
            $file = new Fopen($this->_socket . \DIRECTORY_SEPARATOR . "messages", "a");
            $file->putcsv(\array_merge([$this->pid, \microtime(true)], $message));
        }        

        /*
         * #1 throttles based on socket pid file content (microseconds)
         * #2 dispatching the Cojtroller if exists!
         */

        public function dispatch(string $path): string {
            return (string) $this->getController($path);
        }

        /*
         * returning time the start of this controller
         */

        final public function getTimer() {
            return (float) \microtime(true) - $this->_time;
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
                            $data = \str_replace("false", false, $this->dehydrate($data));
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

        /*
         * executing this controller by dispatching a path and setting that path as reference for follow ups
         */

        public function execute(string $path) {
            $controller = new $this($this->_socket);
            $controller->clone($this->parameters($this->diff()));
            $controller->path = \realpath($this->path . \DIRECTORY_SEPARATOR . \dirname($path));
            if (isset($controller->path)) {
                try {
                    return $controller->getCallbacks($controller->dispatch(\basename($path)));
                } catch (\UnexpectedValueException $ex) {
                    throw new \LogicException(\sprintf("invalid value for parameter %s in %s (%s)", $ex->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                } catch (\InvalidArgumentException $ex) {
                    throw new \LogicException(\sprintf("parameter %s required in %s (%s)", $ex->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                } catch (\ErrorException | \TypeError | \ParseError | \Error $ex) {
                    throw new \LogicException(\sprintf("%s in %s (%s)", $ex->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                }
            }
        }

        public function __destruct() {            
            if (\file_exists($this->_socket . \DIRECTORY_SEPARATOR . $this->pid)) {
                \unlink($this->_socket . \DIRECTORY_SEPARATOR . $this->pid);
                $this->update(["destroyed"]);
            }
        }
    }

}
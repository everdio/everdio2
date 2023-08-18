<?php

namespace Component\Core {

    use \Component\Validation,
        \Component\Validator;

    abstract class Controller extends \Component\Core {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "time" => new Validation(\microtime(true), [new Validator\IsFloat]),
                "path" => new Validation(false, [new Validator\IsString\IsDir]),
                "debug" => new Validation(false, [new Validator\IsString]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "arguments" => new Validation(false, [new Validator\IsString, new Validator\IsString\IsPath]),
                "reserved" => new Validation(false, [new Validator\IsArray])                
                    ] + $_parameters);

            $this->reserved = $this->diff();
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
        
        final public function getTime() {
            return (float) \microtime(true) - $this->time;
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
            $controller = new $this;
            $controller->store($this->restore($this->reserved));
            $controller->time = \microtime(true);
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
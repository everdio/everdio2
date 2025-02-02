<?php

namespace Component\Core {

    use \Component\Validation,
        \Component\Validator;

    abstract class Controller extends \Component\Core {

        use Threading;

        public function __construct(array $_parameters = []) {
            parent::__construct(\array_merge([
                "time" => new Validation(false, [new Validator\IsFloat, new Validator\IsInteger]),
                "path" => new Validation(false, [new Validator\IsString\IsDir]),
                "basename" => new Validation(false, [new Validator\IsString]),
                "debug" => new Validation(false, [new Validator\IsString]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "arguments" => new Validation(false, [new Validator\IsString, new Validator\IsString\IsPath]),
                "storage" => new Validation(\sys_get_temp_dir(), [new Validator\IsString, new Validator\IsString\IsDir]),
                "reserved" => new Validation(false, [new Validator\IsArray])
                    ], $_parameters));

            $this->reserved = $this->diff();
        }

        /*
         * dispatching the Cojtroller if exists!
         */

        public function dispatch(string $path): string|null|int {
            return $this->getController($path);
        }

        /*
         * process timer using the server global request_time_float
         */

        final public function timing(): float {
            return (float) (\microtime(true) - $this->time);
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

                return \ob_get_clean();
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
         * executing this controller by dispatching a path and setting that path as a new reference pointer for dispatches
         */

        final public function execute(string $path, array $request = []) {
            $controller = new $this;
            $controller->import($this->export($this->reserved));
            $controller->request->store($request);
            $controller->path = \realpath($this->path . \DIRECTORY_SEPARATOR . \dirname($path));
            $controller->basename = \basename($path);

            if (isset($controller->path)) {
                try {
                    return $controller->dispatch($this->basename);
                } catch (\InvalidArgumentException | \UnexpectedValueException | \ValueError | \ErrorException $ex) {
                    throw new \RuntimeException(\sprintf("%s: %s in %s(%s)", \get_class($ex), $ex->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                }
            }
        }

        public function __clone() {
            $controller = parent::__clone();
            $controller->import($this->export($this->reserved));
            return (object) $controller;
        }
    }

}
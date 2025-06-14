<?php

namespace Component\Core\Adapter\Wrapper {

    use \Component\Validation,
        \Component\Validator;

    abstract class Controller extends \Component\Core\Adapter\Wrapper {
        public function __construct(array $_parameters = []) {
            parent::__construct(\array_merge([
                "time" => new Validation(false, [new Validator\IsFloat, new Validator\IsInteger]),
                "ip" => new Validation(false, [new Validator\IsString, new Validator\Len\Smaller(15)]),                
                "path" => new Validation(false, [new Validator\IsString\IsDir]),                                                
                "hostname" => new Validation(false, [new Validator\IsString]),
                "basename" => new Validation(false, [new Validator\IsString]),
                "debug" => new Validation(false, [new Validator\IsString]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "arguments" => new Validation(false, [new Validator\IsString, new Validator\IsString\IsPath]),
                "reserved" => new Validation(false, [new Validator\IsArray])
                            ], $_parameters));
            
            $this->adapter = ["ip", "path", "hostname"];            
            $this->reserved = $this->diff();
        }

        /*
         * dispatching the Cojtroller if exists!
         */

        public function dispatch(string $path) {
            if (\is_file($this->path . \DIRECTORY_SEPARATOR . $path . ".php")) {
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
            $controller = \unserialize(\serialize($this));
            $controller->request->store($request);
            $controller->path = \realpath($this->path . \DIRECTORY_SEPARATOR . \dirname($path));
            $controller->basename = \basename($path);

            if (isset($controller->path) && isset($controller->basename)) {
                try {
                    return $controller->dispatch($controller->basename);
                } catch (\InvalidArgumentException | \UnexpectedValueException | \ValueError | \ErrorException $ex) {
                    throw new \RuntimeException(\sprintf("%s: %s in %s(%s)", \get_class($ex), $ex->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                }
            }
        }
    }

}
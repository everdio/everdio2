<?php

namespace Component\Core\Adapter\Wrapper {

    use \Component\Validation,
        \Component\Validator;

    abstract class Controller extends \Component\Core\Adapter\Wrapper {

        public function __construct(array $_parameters = []) {
            parent::__construct(\array_merge([
                "time" => new Validation(false, [new Validator\IsFloat, new Validator\IsInteger]),
                "path" => new Validation(false, [new Validator\IsString\IsDir]),
                "basename" => new Validation(false, [new Validator\IsString]),
                "debug" => new Validation(false, [new Validator\IsString, new Validator\IsInteger]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "routing" => new Validation(false, [new Validator\IsString, new Validator\IsString\IsPath]),
                "output" => new Validation(false, [new Validator\IsString]),
                "reserved" => new Validation(false, [new Validator\IsArray])
                            ], $_parameters));

            $this->reserved = $this->diff();
        }

        /*
         * dispatching the Controller if exists!
         */

        public function dispatch(string $path) {
            if (\is_file($this->path . \DIRECTORY_SEPARATOR . $path . ".php")) {
                \ob_start();
                require $this->path . \DIRECTORY_SEPARATOR . $path . ".php";
                return \ob_get_clean();
            }
        }

        final public function isRoute(string $route): bool {
            return (bool) (isset($this->routing) && ((string) \implode(\DIRECTORY_SEPARATOR, \array_intersect_assoc(\explode(\DIRECTORY_SEPARATOR, $route), \explode(\DIRECTORY_SEPARATOR, $this->routing))) === $route));
        }

        final public function getDebug(): bool {
            return (bool) isset($this->request->{$this->debug});
        }

        /*
         * processing callbacks from $content {{string}}
         */

        final public function getCallbacks(string $content, array $matches = []): string {
            if (\is_string($content) && \preg_match_all("!\{\{(.+?)\}\}!", $content, $matches, \PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $key => $match) {
                    try {
                        if (!\is_string(($data = $this->callback($match)))) {
                            $data = \str_replace("false", "", $this->dehydrate($data));
                        }
                    } catch (\BadMethodCallException $ex) {
                        throw new \LogicException(\sprintf("BAD_METHOD_CALL: %s", $match), 0, $ex);
                    } catch (\BadFunctionCallException $ex) {
                        throw new \LogicException(\sprintf("BAD_FUNCTION_CALL: %s", $match), 0, $ex);
                    }

                    $content = \str_replace($matches[0][$key], $data, $content);
                }
            }

            return (string) $content;
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
                } catch (\LogicException | \InvalidArgumentException | \UnexpectedValueException | \Error | \ValueError | \ErrorException $ex) {
                    throw new \RuntimeException(\sprintf("ERROR: %s", $ex->getMessage()), 0, $ex);
                }
            }
        }
    }

}
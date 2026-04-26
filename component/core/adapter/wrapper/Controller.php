<?php

namespace Component\Core\Adapter\Wrapper {

    use \Component\Validation,
        \Component\Validator;

    abstract class Controller extends \Component\Core\Adapter\Wrapper {

        public function __construct(array $_parameters = []) {
            parent::__construct(\array_merge([
                "ip" => new Validation(false, [new Validator\IsString, new Validator\Len\Smaller(15)]),                
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
            $this->adapter = ["ip"];            
        }

        /*
         * dispatching the Controller if exists!
         * @param string $path
         * @return type
         */
        public function dispatch(string $path) {
            if (\is_file($this->path . \DIRECTORY_SEPARATOR . $path . ".php")) {
                \ob_start();
                require $this->path . \DIRECTORY_SEPARATOR . $path . ".php";
                return \ob_get_clean();
            }
        }
        
        protected function addAdapter(): object {
            return (object) new \Component\Caller\Ssh2($this->ip);
        }        

        /**
         *  path intersector for routing
         * @param string $route
         * @return bool
         */
        final public function isRoute(string $route): bool {
            return (bool) (isset($this->routing) && ((string) \implode(\DIRECTORY_SEPARATOR, \array_intersect_assoc(\explode(\DIRECTORY_SEPARATOR, $route), \explode(\DIRECTORY_SEPARATOR, $this->routing))) === $route));
        }

        /**
         * Easy/quick debug mode checker
         * @return bool
         */
        final public function isDebug(): bool {
            return (bool) isset($this->request->{$this->debug});
        }

        /*
         * Finding and executing(->callback) potentional callbacks from $content {{string}}
         * @param string $content
         * @param array $matches
         * @return string
         * @throws \LogicException
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
         * Executing this controller by dispatching a path and setting that path as a new reference pointer for dispatches
         * @param string $path
         * @param array $request
         * @return type
         * @throws \RuntimeException
         */

        final public function execute(string $path, array $request = []) {
            $controller = \unserialize(\serialize($this));
            $controller->request->store($request);
            $controller->path = \realpath($this->path . \DIRECTORY_SEPARATOR . \dirname($path));
            $controller->basename = \basename($path);

            if (isset($controller->path) && isset($controller->basename)) {
                try {
                    return $controller->dispatch($controller->basename);
                } catch (\Exception | \Error $ex) {
                    throw new \RuntimeException(\sprintf("%s: %s", \get_class($ex), $ex->getMessage()), 0, $ex);
                }
            }
        }
    }

}
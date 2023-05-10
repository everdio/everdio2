<?php
namespace Component\Core {
    use \Component\Validation, \Component\Validator, \Component\Caller\File\Fopen;
    abstract class Controller extends \Component\Core {            
        public function __construct(array $_parameters = []) {
            parent::__construct([          
                "time" => new Validation(\microtime(true), [new Validator\IsFloat]),
                "pid" => new Validation(\posix_getpid(), [new Validator\IsInteger]),
                "token" => new Validation(\bin2hex(\openssl_random_pseudo_bytes(32)), [new Validator\IsString, new Validator\Len\Bigger(45)]),
                "path" => new Validation(false, [new Validator\IsString\IsPath\IsReal]),
                "debug" => new Validation(false, [new Validator\IsString]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject\Of("\Component\Core\Parameters")]),
                "arguments" => new Validation(false, [new Validator\IsString])
            ] + $_parameters);    
        }
        
        /*
         * fetching load from linux systems
         */
        private function _load() : float {
            $fopen = new \Component\Caller\File\Fopen("/proc/loadavg");
            return (float) $this->hydrate($fopen->gets(5));
        }
        
        /*
         * fetching amount of cpu cores on linux systems
         */
        private function _cores() : int {
            return (int) $this->hydrate(\exec("nproc"));
        }
        
        /*
         * calculating nicesess based on load (1 core is max 0.50)
         */
        private function _priority(int $factor = 3) : int {
            return (int) round((($this->_load() / ($this->_cores() / $factor)) * 100) * (39 / 100) - 19);
        }
        
        /*
         * setting process nicess based on priority and process id
         */
        private function _renice(int $pid, int $priority) {
            \exec(\sprintf("renice %s %s", $priority, $pid));
        }             

        /*
         * fetching processes based on pm (process manager) and resetting nicesess
         * all running proceses matching the pm will be resetted based on current load
         */
        final public function throttle(array $processmanagers, int $factor = 3) {
            if (\strtolower(\PHP_OS) === "linux") {
                foreach (\glob("/proc/*/status") as $entry) {
                    if (\is_integer(($pid = $this->hydrate(\basename(\dirname($entry)))))) {
                        try {
                            $fopen = new \Component\Caller\File\Fopen($entry);
                            if (\in_array(\str_replace("Name:\t", "", \trim($fopen->gets())), $processmanagers)) {
                                $this->_renice($pid, $this->_priority($factor));
                            }
                        } catch (\ErrorException $ex) {
                            //ignore since the process is already gone
                        }
                    }
                }
            }
        }        
        
        /*
         * checking if a path matches the current arguments
         */
        final public function isRoute(string $path) : bool {
            return (bool) (isset($this->arguments) && \implode(\DIRECTORY_SEPARATOR, \array_intersect_assoc(\explode(\DIRECTORY_SEPARATOR, (string) $path), \explode(\DIRECTORY_SEPARATOR, $this->arguments))) === (string) $path);
        }        

        /*
         * dispatching the Cojtroller if exists!
         */
        public function dispatch(string $path) {
            return (string) $this->getController($path);
        }            

        /*
         * returning time the start of this controller
         */
        final public function getTimer(int $round = 3) {
            return (float) \round(\microtime(true) - $this->time, $round);
        }
        
        /*
         * checks if controller php file exists
         */
        final public function hasController(string $path) : bool {
            return (bool) \file_exists($this->path . \DIRECTORY_SEPARATOR . $path . ".php");
        }        
  
        /*
         * including php file and catching it's output for returning
         */
        final public function getController(string $path) {            
            if ($this->hasController($path)) {
                \ob_start();
                require $this->path . \DIRECTORY_SEPARATOR . $path . ".php";
                return (string) $this->getCallbacks(\ob_get_clean());
            }            
        }            
        
        /*
         * processing any existing callbacks from output string
         */
        final public function getCallbacks(string $output, array $matches = []) : string {            
            if (\is_string($output) && \preg_match_all("!\{\{(.+?)\}\}!", $output, $matches, \PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $key => $match) {
                    try {
                        if (!\is_string($data = $this->callback($match))) {
                            $data = \str_replace("false", false, $this->dehydrate($data));
                        }                 
                    } catch (\InvalidArgumentException $ex) {
                        throw new \LogicException(\sprintf("invalid arguments %s in %s", $ex->getPrevious()->getMessage(), $match));
                    } catch (\BadMethodCallException $ex) {
                        throw new \LogicException(\sprintf("bad method call %s in %s", $ex->getPrevious()->getMessage(), $match));
                    } catch (\BadFunctionCallException $ex) { 
                        throw new \LogicException(\sprintf("bad function call %s in %s", $ex->getPrevious()->getMessage(), $match));
                    } catch (\ErrorException | \TypeError | \ParseError | \Error $ex) {
                        throw new \LogicException(\sprintf("%s %s in %s", \get_class($ex), $ex->getMessage(), $match));                 
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
            $controller->clone($this->parameters($this->diff()));
            $controller->path = \realpath($this->path . \DIRECTORY_SEPARATOR . \dirname($path));
            if (isset($controller->path)) {
                try {
                    return $controller->dispatch(\basename($path));        
                } catch (\UnexpectedValueException $ex) {
                    throw new \LogicException(\sprintf("invalid value for parameter %s: %s in %s (%s)", $ex->getMessage(), $ex->getPrevious()->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                } catch (\InvalidArgumentException $ex) {
                    throw new \LogicException(\sprintf("parameter %s required in %s (%s)", $ex->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                } catch (\ErrorException | \TypeError | \ParseError | \Error $ex) {
                    throw new \LogicException(\sprintf("%s in %s (%s)", $ex->getMessage(), $ex->getFile(), $ex->getLine()), 0, $ex);
                } catch (\LogicException $ex) {
                    throw $ex;
                }
            }
        }
    }    
}
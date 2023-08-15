<?php
namespace Component\Core {
    use \Component\Validation, \Component\Validator;
    abstract class Controller extends \Component\Core {            
        public function __construct(array $_parameters = []) {
            parent::__construct([          
                "_time" => new Validation(\microtime(true), [new Validator\IsFloat]),
                "_osid" => new Validation(\strtolower(\PHP_OS), [new Validator\IsString\InArray(["linux"])]),
                "_token" => new Validation(\bin2hex(\openssl_random_pseudo_bytes(32)), [new Validator\IsString, new Validator\Len\Bigger(45)]),
                "_reserved" => new Validation(false, [new Validator\IsArray]),
                "pid" => new Validation(\posix_getpid(), [new Validator\IsInteger]),                
                "path" => new Validation(false, [new Validator\IsString\IsPath\IsReal]),
                "debug" => new Validation(false, [new Validator\IsString]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "arguments" => new Validation(false, [new Validator\IsString, new Validator\IsString\IsPath]),
                "sockets" => new Validation(false, [new Validator\IsString\IsPath]),
                "bus" => new Validation(false, [new Validator\IsString\IsPath])
            ] + $_parameters);    
            
            $this->_reserved = $this->diff();
        }          
        
        /*
         * fetching avg load (linux)
         */
        final protected function getLoad() : float {
            return (float) \substr(\file_get_contents("/proc/loadavg"), 0, 5);  
        }        
        
        final protected function getSockets() : array {
            return (array) \glob($this->sockets . \DIRECTORY_SEPARATOR . "*");
        }
        
        final protected function getPriority(float | int $load) : int {
            return (int) \round(($load * 100) * (39 / 100) - 19);
        }
        
        final protected function throttle() : void {
            if (\is_file($this->sockets . \DIRECTORY_SEPARATOR . $this->pid)) {
                \usleep(\file_get_contents($this->sockets . \DIRECTORY_SEPARATOR . $this->pid));
            }            
        }

        final public function renice() : void {
            $priority = $this->getPriority($this->getLoad());
            
            foreach ($this->getSockets() as $file) {
                if (\file_exists("/proc/" . \basename($file))) {
                    \exec(\sprintf("renice %s %s", $priority, \basename($file)));
                }
            }
        }             
        
        final public function timers(int $usleep = 3000) : void {
            $usleep = \round($usleep * $this->getLoad());

            foreach (\array_merge([$this->sockets . \DIRECTORY_SEPARATOR . $this->pid], $this->getSockets()) as $file) {
                \file_put_contents($file, $usleep);
            }

            \chmod($this->sockets . \DIRECTORY_SEPARATOR . $this->pid, 0770);
        }         

        /*
         * checking if a path matches the current arguments
         */
        final public function isRoute(string $path) : bool {
            return (bool) (isset($this->arguments) && \implode(\DIRECTORY_SEPARATOR, \array_intersect_assoc(\explode(\DIRECTORY_SEPARATOR, (string) $path), \explode(\DIRECTORY_SEPARATOR, $this->arguments))) === (string) $path);
        }        

        /*
         * #1 throttles based on socket pid file content (microseconds)
         * #2 dispatching the Cojtroller if exists!
         */
        public function dispatch(string $path) : string {
            $this->throttle();
            
            return (string) $this->getController($path);
        }            

        /*
         * returning time the start of this controller
         */
        final public function getTimer(int $round = 3) {
            return (float) \round(\microtime(true) - $this->_time, $round);
        }
        
        /*
         * checks if controller php file exists
         */
        final public function hasController(string $path) : bool {
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
        final public function getCallbacks(string $output, array $matches = []) : string {            
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
            $controller->clone($this->parameters($this->diff()));
            $controller->timers();
            $controller->renice();            
            $controller->path = \realpath($this->path . \DIRECTORY_SEPARATOR . \dirname($path));
            if (isset($controller->path)) {
                try {
                    return $this->getCallbacks($controller->dispatch(\basename($path)));
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
            foreach ($this->getSockets() as $file) {
                if ($this->pid === (int) \basename($file) || !\file_exists("/proc/" . \basename($file))) {
                    \unlink($file);
                }
            }                            
        }
    }    
}
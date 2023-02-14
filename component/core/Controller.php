<?php
namespace Component\Core {
    use \Component\Validation, \Component\Validator, \Component\Caller\File\Fopen;
    abstract class Controller extends \Component\Core {            
        public function __construct(array $_parameters = []) {
            parent::__construct([                
                "time" => new Validation(\microtime(true), [new Validator\IsFloat]),
                "pm" => new Validation(false, [new Validator\IsString]),
                "pid" => new Validation(posix_getpid(), [new Validator\IsInteger]),
                "threshold" => new Validation([-20 => 1, -10 => 10, 0 => 60, 10 => 120, 19 => 180], [new Validator\IsArray]),
                "token" => new Validation(\bin2hex(\openssl_random_pseudo_bytes(32)), [new Validator\IsString, new Validator\Len\Bigger(45)]),
                "path" => new Validation(false, [new Validator\IsString\IsPath\IsReal]),
                "debug" => new Validation(false, [new Validator\IsString]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject\Of("\Component\Core\Parameters")]),
                "arguments" => new Validation(false, [new Validator\IsString])
            ] + $_parameters);    
        }

        final public function throttle(int $time = 0) : int {
            foreach (\glob("/proc/*/status") as $entry) {
                $fopen = new \Component\Caller\File\Fopen($entry, "r");
                if (\str_replace("Name:\t", "", \trim($fopen->gets())) === $this->pm) {                    
                    $time += \time() - \filectime($fopen->getPath());
                }
            }
            
            foreach ($this->threshold as $priority => $limit) {
                if ($time <= $limit) {
                    return (int) $priority;
                }
            }
            
            return (int) $priority;
        }
        
        final public function renice(int $priority = 0) {
            \exec(\sprintf("renice %s %s", $priority, $this->pid));
        }        
        
        final public function isRoute(string $path) : bool {
            return (bool) (isset($this->arguments) && \implode(\DIRECTORY_SEPARATOR, \array_intersect_assoc(\explode(\DIRECTORY_SEPARATOR, (string) $path), \explode(\DIRECTORY_SEPARATOR, $this->arguments))) === (string) $path);
        }        

        public function dispatch(string $path) {
            return (string) $this->getController($path);
        }    
        
        final public function getSubstring(string $string, int $start = 0, $length = 25, string $prefix = NULL, string $suffix = NULL, bool $fill = false, $encoding = "UTF-8") : string {
            return (string) (\strlen($string) >= $length ? $prefix . \mb_substr($string, $start, $length, $encoding) . $suffix : ($fill ? \str_pad($string, $length + \strlen($suffix), " ", \STR_PAD_RIGHT) : $string));
        }         
        
        final public function getTime(int $round = 3) {
            return (float) \round(\microtime(true) - $this->time, $round);
        }
  
        final public function getController(string $path) {            
            if (\file_exists(($file = \sprintf("%s/%s.php", $this->path, $path)))) {
                \ob_start();
                require $file;
                return (string) $this->getCallbacks(\ob_get_clean());
            }            
        }            
        
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
                    }
                    
                    $output = \str_replace($matches[0][$key], $data, $output);
                }                            
            }
            
            return (string) $output;
        }        

        public function execute(string $path) {      
            $controller = new $this;
            $controller->import($this->export($this->diff()));
            $controller->path = \realpath($this->path . \DIRECTORY_SEPARATOR . \dirname($path));

            if (isset($controller->path)) {
                try {
                    return $controller->dispatch(\basename($path));        
                } catch (\UnexpectedValueException $ex) {
                    throw new \LogicException(\sprintf("invalid value for parameter %s: %s", $ex->getMessage(), $ex->getPrevious()->getMessage()), 0, $ex);
                } catch (\InvalidArgumentException $ex) {
                    throw new \LogicException(\sprintf("parameter %s required", $ex->getMessage()), 0, $ex);
                } catch (\ErrorException | \TypeError | \Error $ex) {
                    throw new \LogicException(\sprintf("error %s", $ex->getMessage()), 0, $ex);
                } catch (\LogicException $ex) {
                    throw $ex;
                }
            }
        }
    }    
}
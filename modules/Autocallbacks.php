<?php   
namespace Modules {
    use \Component\Validation, \Component\Validator;
    class Autocallbacks extends \Component\Core\Controller\Model\Http {
        public function __construct(array $_parameters = []) {
            parent::__construct([
                "controller" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject\Of("\Component\Core\Parameters")]),
                ] + $_parameters);
        }
        
        public function autoCallbacks(string $parameter, float $time = 0.001) : void {
            if (isset($this->{$parameter})) {          
                foreach ($this->{$parameter}->restore() as $mapper => $callbacks) {
                    if (isset($this->library->{$mapper}) ) {
                        if (($object = ($this->library->{$mapper} === \get_class($this) ? $this : new $this->library->{$mapper}))) {
                            foreach ($callbacks as $label => $callback) {   
                                try {            
                                    $previous = $this->getTimer(2);
                                    
                                    if (\is_string($label)) {
                                        $this->controller->store([$mapper => [$label => $object->callback($this->getCallbacks($callback))]]);
                
                                        //continue if static value is controller value or break if static value is not controller value
                                        //[continue] or [break]
                                        if ((isset($this->continue->{$mapper}->{$label}) && $this->continue->{$mapper}->{$label} != $this->controller->{$mapper}->{$label}) || (isset($this->break->{$mapper}->{$label}) && $this->break->{$mapper}->{$label} == $this->controller->{$mapper}->{$label})) {
                                            unset ($this->controller->{$mapper}->{$label});
                                            return;
                                        }

                                        //is or isnot on callback value
                                        //[is] or [isnot]
                                        if ((isset($this->is->{$mapper}->{$label}) && isset($this->controller->{$mapper}->{$label}) && $this->callback($this->is->{$mapper}->{$label}) != $this->controller->{$mapper}->{$label}) || (isset($this->isnot->{$mapper}->{$label}) && isset($this->controller->{$mapper}->{$label}) && $this->callback($this->isnot->{$mapper}->{$label}) == $this->controller->{$mapper}->{$label})) {
                                            unset ($this->controller->{$mapper}->{$label});                                            
                                            return;
                                        }
                                        
                                        //foreach loop
                                        //[foreach]
                                        if (isset($this->foreach->{$mapper}->{$label}) && (isset($this->controller->{$mapper}->{$label}) && $this->controller->{$mapper}->{$label} instanceof \Component\Core\Parameters)) {
                                            foreach ($this->controller->{$mapper}->{$label}->restore() as $foreach) {
                                                $this->controller->store([$mapper => [$label => $foreach]]); 
                                                $this->callback($this->getCallbacks($this->foreach->{$mapper}->{$label}));
                                                unset ($this->controller->{$mapper}->{$label});
                                            }   
                                        }
                                    } else {
                                        $object->callback($this->getCallbacks($callback));   
                                    }
                                    
                                    if (isset($this->debug) && isset($this->request->{$this->debug}) && ($diff = $this->getTimer(\strlen($time) - 2) - $previous) >= $time) {
                                        echo "<!-- " . $diff . " - " . $parameter . "/" . $mapper . "[" . $label . "]" . "=" . \str_replace(["{{", "}}"], false, $callback) . "-->" . \PHP_EOL;
                                    }
                                    
                                } catch (\BadMethodCallException | \UnexpectedValueException | \InvalidArgumentException | \ErrorException | \ValueError | \TypeError | \ParseError | \Error $ex) {
                                    echo "<!-- " . \PHP_EOL;
                                    print_r($this->controller->restore());
                                    echo "-->" . \PHP_EOL;
                                    throw new \LogicException(\sprintf("%s/%s/%s/%s: %s", $parameter, $mapper, $label, \trim($callback,"{{}}"), $ex->getMessage()), 0, $ex);
                                }                                    
                            }
                        }
                    }
                }
            }                  
        }
    }
}

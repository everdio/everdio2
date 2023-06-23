<?php   
namespace Modules {
    use \Component\Validation, \Component\Validator;
    class Autocallbacks extends \Component\Core\Controller\Model\Http {
        public function __construct(array $_parameters = []) {
            parent::__construct([
                "controller" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject\Of("\Component\Core\Parameters")]),
                "calledbacks" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject\Of("\Component\Core\Parameters")])
                ] + $_parameters);
        }
        
        public function autoCallbacks(string $parameter) : void {
            if (isset($this->{$parameter})) {          
                foreach ($this->{$parameter}->restore() as $mapper => $callbacks) {
                    if (isset($this->library->{$mapper}) ) {
                        if (($object = ($this->library->{$mapper} === \get_class($this) ? $this : new $this->library->{$mapper}))) {
                            foreach ($callbacks as $label => $callback) {   
                                try {                                    
                                    $this->calledbacks->store([$parameter => [$mapper => [$label => ($calledbacks = $this->getCallbacks($callback))]]]);
                                    
                                    if (\is_string($label)) {
                                        $this->controller->store([$mapper => [$label => $object->callback($calledbacks)]]);
                
                                        //break if static value is not controller value
                                        //[continue]
                                        if (isset($this->continue->{$mapper}->{$label}) && $this->continue->{$mapper}->{$label} != $this->controller->{$mapper}->{$label}) {
                                            unset ($this->controller->{$mapper}->{$label});
                                            return;
                                        }

                                        //continue if static value is controller value
                                        //[break]
                                        if (isset($this->break->{$mapper}->{$label}) && $this->break->{$mapper}->{$label} == $this->controller->{$mapper}->{$label}) {
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

                                            unset ($this->controller->{$mapper}->{$label});
                                        }
                                    } else {
                                        $object->callback($calledbacks);   
                                    }
                                } catch (\BadMethodCallException | \UnexpectedValueException | \InvalidArgumentException | \ErrorException | \ValueError | \TypeError | \ParseError | \Error $ex) {
                                    if (isset($this->request->{$this->debug})) {
                                        echo "<pre>";
                                        \print_r($this->calledbacks->restore());
                                        echo "</pre>";
                                    }

                                    throw new \LogicException(\sprintf("%s/%s/%s: %s", $parameter, $mapper, $label, $ex->getMessage()), 0, $ex);
                                }                                    
                            }
                        }
                    }
                }
            }                  
        }
    }
}

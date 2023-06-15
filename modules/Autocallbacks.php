<?php   
namespace Modules {
    use \Component\Validation, \Component\Validator;
    class Autocallbacks extends \Component\Core\Controller\Model\Http {
        public function __construct(array $_parameters = []) {
            parent::__construct([
                "controller" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject\Of("\Component\Core\Parameters")])
                ] + $_parameters);
        }
        
        public function autoCallbacks(string $parameter) : void {
            if (isset($this->{$parameter})) {          
                foreach ($this->{$parameter}->restore() as $mapper => $callbacks) {
                    if (isset($this->library->{$mapper}) ) {
                        if (($object = ($this->library->{$mapper} === \get_class($this) ? $this : new $this->library->{$mapper})) && \method_exists($object, "callback")) {
                            try {
                                foreach ($callbacks as $label => $callback) {      
                                    if (\is_string($label)) {
                                        $this->controller->store([$mapper => [$label => $object->callback($this->getCallbacks($callback))]]);

                                        //break if static value is not controller value
                                        if (isset($this->continue->{$mapper}->{$label}) && $this->continue->{$mapper}->{$label} != $this->controller->{$mapper}->{$label}) {
                                            unset ($this->controller->{$mapper}->{$label});
                                            return;
                                        }

                                        //continue if static value is controller value
                                        if (isset($this->break->{$mapper}->{$label}) && $this->break->{$mapper}->{$label} == $this->controller->{$mapper}->{$label}) {
                                            unset ($this->controller->{$mapper}->{$label});
                                            return;                                            
                                        }

                                        //is or isnot on callback value
                                        if ((isset($this->is->{$mapper}->{$label}) && isset($this->controller->{$mapper}->{$label}) && $this->callback($this->is->{$mapper}->{$label}) != $this->controller->{$mapper}->{$label}) || (isset($this->isnot->{$mapper}->{$label}) && isset($this->controller->{$mapper}->{$label}) && $this->callback($this->isnot->{$mapper}->{$label}) == $this->controller->{$mapper}->{$label})) {
                                            unset ($this->controller->{$mapper}->{$label});                                            
                                            return;
                                        }

                                        //foreach loop
                                        if (isset($this->foreach->{$mapper}->{$label}) && isset($this->controller->{$mapper}->{$label})) {
                                            foreach ($this->controller->{$mapper}->{$label}->restore() as $foreach) {
                                                $this->controller->store([$mapper => [$label => $foreach]]); 
                                                $this->callback($this->getCallbacks($this->foreach->{$mapper}->{$label}));
                                                unset ($this->controller->{$mapper}->{$label});
                                            }     

                                            unset ($this->controller->{$mapper}->{$label});
                                        }
                                    } else {
                                        $object->callback($this->getCallbacks($callback));   
                                    }
                                }
                            } catch (\UnexpectedValueException | InvalidArgumentException | ErrorException | \TypeError | \ParseError | \Error $ex) {
                                throw new \LogicException(\sprintf("%s/%s: %s", $parameter, $mapper, $ex->getMessage()));
                            }
                        }
                    }
                }
            }
        }
    }
}

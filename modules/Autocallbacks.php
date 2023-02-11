<?php   
namespace Modules {
    trait Autocallbacks {
        final public function autocallbacks(string $parameter) : void {
            if (isset($this->{$parameter})) {                
                foreach ($this->{$parameter}->restore() as $object => $callbacks) {
                    if (isset($this->library->{$object}) ) {
                        $finder = ($this->library->{$object} === \get_class($this) ? $this : new $this->library->{$object});
                        foreach ($callbacks as $label => $callback) {                              
                            try {
                                if (isset($this->request->{$this->debug})) {
                                    echo "<!--time: " . $this->getTime(3) . "s callback: " . $parameter . "/controller/" . $object . "/" . $label . "-->" . PHP_EOL;
                                }
                                                            
                                if (\is_string($label)) {
                                    $this->controller->store([$object => [$label => $finder->callback($this->getCallbacks($callback))]]);

                                    //break if static value is not controller value
                                    if (isset($this->continue->{$object}->{$label}) && $this->continue->{$object}->{$label} != $this->controller->{$object}->{$label}) {
                                        unset ($this->controller->{$object}->{$label});
                                        return;
                                    }

                                    //continue if static value is controller value
                                    if (isset($this->break->{$object}->{$label}) && $this->break->{$object}->{$label} == $this->controller->{$object}->{$label}) {
                                        unset ($this->controller->{$object}->{$label});
                                        return;                                            
                                    }

                                    //is or isnot on callback value
                                    if ((isset($this->is->{$object}->{$label}) && $this->callback($this->is->{$object}->{$label}) != $this->controller->{$object}->{$label}) || (isset($this->isnot->{$object}->{$label}) && $this->callback($this->isnot->{$object}->{$label}) == $this->controller->{$object}->{$label})) {
                                        unset ($this->controller->{$object}->{$label});                                            
                                        return;
                                    }

                                    //foreach loop
                                    if (isset($this->foreach->{$object}->{$label}) && isset($this->controller->{$object}->{$label})) {
                                        foreach ($this->controller->{$object}->{$label}->restore() as $key => $foreach) {
                                            $this->controller->store([$object => [$label => $foreach]]); 
                                            $this->callback($this->getCallbacks($this->foreach->{$object}->{$label}));
                                            unset ($this->controller->{$object}->{$label});
                                        }                   
                                    }
                                } else {
                                    $finder->callback($this->getCallbacks($callback));
                                }      
                            } catch (\UnexpectedValueException $ex) {
                                throw new \LogicException(\sprintf("invalid value for parameter %s: %s", $ex->getMessage(), $ex->getPrevious()->getMessage()), 0, $ex);
                            } catch (\InvalidArgumentException $ex) {
                                throw new \LogicException(\sprintf("parameter %s required", $ex->getMessage()), 0, $ex);
                            } catch (\ErrorException | \TypeError | \Error $ex) {
                                throw new \LogicException($ex->getMessage(), 0, $ex);
                            } catch (\LogicException $ex) {
                                throw new \RuntimeException(\sprintf("%s/controller/%s/%s: %s", $parameter, $object, $label, $ex->getMessage()));
                            }
                        }                            
                    }
                }                
            }                          
        }
    }
}

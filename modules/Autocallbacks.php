<?php   
namespace Modules {
    trait Autocallbacks {
        final public function autocallbacks(string $parameter) : void {
            if (isset($this->{$parameter})) {                
                foreach ($this->{$parameter}->restore() as $label => $callbacks) {
                    if (isset($this->library->{$label}) ) {
                        $call = ($this->library->{$label} === \get_class($this) ? $this : new $this->library->{$label});
                        foreach ($callbacks as $id => $callback) {                              
                            try {
                                if (isset($this->request->_debug)) {
                                    echo "<!--autocallback: " . $parameter . " => controller / " . $label . " / " . $id . " :: " . $this->getCallbacks($callback) . "-->" . PHP_EOL;
                                }
                                                            
                                if (\is_string($id)) {
                                    $this->controller->store([$label => [$id => $call->callback($this->getCallbacks($callback))]]);

                                    //break if static value is not controller value
                                    if (isset($this->continue->{$label}->{$id}) && $this->continue->{$label}->{$id} != $this->controller->{$label}->{$id}) {
                                        unset ($this->controller->{$label}->{$id});
                                        return;
                                    }

                                    //continue if static value is controller value
                                    if (isset($this->break->{$label}->{$id}) && $this->break->{$label}->{$id} == $this->controller->{$label}->{$id}) {
                                        unset ($this->controller->{$label}->{$id});
                                        return;                                            
                                    }

                                    //is or isnot on callback value
                                    if ((isset($this->is->{$label}->{$id}) && $this->callback($this->is->{$label}->{$id}) != $this->controller->{$label}->{$id}) || (isset($this->isnot->{$label}->{$id}) && $this->callback($this->isnot->{$label}->{$id}) == $this->controller->{$label}->{$id})) {
                                        unset ($this->controller->{$label}->{$id});                                            
                                        return;
                                    }

                                    //foreach loop
                                    if (isset($this->foreach->{$label}->{$id}) && isset($this->controller->{$label}->{$id})) {
                                        foreach ($this->controller->{$label}->{$id}->restore() as $key => $foreach) {
                                            $this->controller->store([$label => [$id => $foreach]]); 
                                            $this->callback($this->getCallbacks($this->foreach->{$label}->{$id}));
                                            unset ($this->controller->{$label}->{$id});
                                        }                   
                                    }
                                } else {
                                    $call->callback($this->getCallbacks($callback));
                                }      
                            } catch (\UnexpectedValueException $ex) {
                                throw new \LogicException(\sprintf("invalid value for parameter %s: %s", $ex->getMessage(), $ex->getPrevious()->getMessage()), 0, $ex);
                            } catch (\InvalidArgumentException $ex) {
                                throw new \LogicException(\sprintf("parameter %s required", $ex->getMessage()), 0, $ex);
                            } catch (\ErrorException | \TypeError | \Error $ex) {
                                throw new \LogicException($ex->getMessage(), 0, $ex);
                            } catch (\LogicException $ex) {
                                throw $ex;
                            }
                        }                            
                    }
                }                
            }                          
        }
    }
}

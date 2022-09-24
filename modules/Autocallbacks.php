<?php   
namespace Modules {
    trait Autocallbacks {
        final public function autocallbacks(string $parameter) {  
            if (isset($this->library) && isset($this->controller) && isset($this->{$parameter})) {                
                foreach ($this->{$parameter}->restore() as $label => $callbacks) {
                    if (isset($this->library->{$label}) ) {
                        $call = ($this->library->{$label} === (string) $this ? clone $this : new $this->library->{$label});
                        if ($call instanceof \Component\Core) {
                            foreach ($callbacks as $key => $callback) {                                
                                try {                      
                                    if (\is_string($key)) {
                                        $this->controller->store([$label => [$key => $call->callback($this->getCallbacks($callback))]]);

                                        //continue or break on static value
                                        if ((isset($this->continue->{$label}->{$key}) && $this->continue->{$label}->{$key} != $this->controller->{$label}->{$key}) || (isset($this->break->{$label}->{$key}) && $this->break->{$label}->{$key} == $this->controller->{$label}->{$key})) {
                                            unset ($this->controller->{$label}->{$key});
                                            return;
                                        }              

                                        //is or isnot on callback value
                                        if ((isset($this->is->{$label}->{$key}) && $this->callback($this->is->{$label}->{$key}) !== $this->controller->{$label}->{$key}) || (isset($this->isnot->{$label}->{$key}) && $this->callback($this->isnot->{$label}->{$key}) === $this->controller->{$label}->{$key})) {
                                            unset ($this->controller->{$label}->{$key});                                            
                                            return;
                                        }
                                        
                                        //foreach loop
                                        if (isset($this->foreach->{$label}->{$key}) && isset($this->controller->{$label}->{$key})) {
                                            foreach ($this->controller->{$label}->{$key}->restore() as $foreach) {
                                                $this->data->store([$label => [$key => $foreach]]); 
                                                $this->callback($this->getCallbacks($this->foreach->{$label}->{$key}));
                                                unset ($this->data->{$label}->{$key});
                                            }                   
                                            
                                            unset ($this->controller->{$label}->{$key});
                                        }

                                        //store data
                                        if (isset($this->data->{$label}) && $this->data->{$label}->exists($key) && isset($this->controller->{$label}->{$key})) {
                                            $this->data->{$label}->{$key} = $this->controller->{$label}->{$key};
                                            unset ($this->controller->{$label}->{$key});
                                        }
                                        
                                    } else {
                                        $call->callback($this->getCallbacks($callback));
                                    }      
                                    
                                } catch (\BadMethodCallException | \BadFunctionCallException | \InvalidArgumentException  $ex) {
                                    throw new \RuntimeException(\sprintf("%s while processing %s/%s/%s", $ex->getMessage(), $label, $key, $callback), false, $ex);
                                } 
                            }                            
                        }                       
                    }
                }                
            }                          
        }
    }
}

<?php   
namespace Modules {
    trait Autocallbacks {
        final public function autocallbacks(string $parameter) {  
            if (isset($this->{$parameter})) {                
                foreach ($this->{$parameter}->restore() as $label => $callbacks) {
                    if (isset($this->library->{$label}) ) {
                        $call = ($this->library->{$label} === (string) $this ? $this : new $this->library->{$label});
                        foreach ($callbacks as $key => $callback) {                                
                            if (isset($this->request->_debug)) {
                                echo $parameter . " => controller / " . $label . " / " . $key . " :: " . $this->getCallbacks($callback) . PHP_EOL;
                            }                

                            if (\is_string($key)) {
                                $this->controller->store([$label => [$key => ($call instanceof \Component\Core ? $call->callback($this->getCallbacks($callback)) : $this->getCallbacks($callback))]]);

                                //continue or break on static value
                                if (isset($this->continue->{$label}->{$key}) && $this->continue->{$label}->{$key} != $this->controller->{$label}->{$key}) {
                                    unset ($this->controller->{$label}->{$key});
                                    return;
                                }

                                if (isset($this->break->{$label}->{$key}) && $this->break->{$label}->{$key} == $this->controller->{$label}->{$key}) {
                                    unset ($this->controller->{$label}->{$key});
                                    return;                                            
                                }

                                //is or isnot on callback value
                                if ((isset($this->is->{$label}->{$key}) && $this->callback($this->is->{$label}->{$key}) != $this->controller->{$label}->{$key}) || (isset($this->isnot->{$label}->{$key}) && $this->callback($this->isnot->{$label}->{$key}) == $this->controller->{$label}->{$key})) {
                                    unset ($this->controller->{$label}->{$key});                                            
                                    return;
                                }

                                //foreach loop
                                if (isset($this->foreach->{$label}->{$key}) && isset($this->controller->{$label}->{$key})) {
                                    foreach ($this->controller->{$label}->{$key}->restore() as $foreach) {
                                        $this->controller->store([$label => [$key => $foreach]]); 
                                        $this->callback($this->getCallbacks($this->foreach->{$label}->{$key}));
                                        unset ($this->controller->{$label}->{$key});
                                    }                   
                                }
                            } else {
                                $call->callback($this->getCallbacks($callback));
                            }      
                        }                            
                    }
                }                
            }                          
        }
    }
}

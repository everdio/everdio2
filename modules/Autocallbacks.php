<?php   
namespace Modules {
    use \Component\Validation, \Component\Validator;
    abstract class Autocallbacks extends \Component\Core\Controller\Model\Http {
        public function __construct(array $_parameters = []) {
            parent::__construct([
                "controller" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject\Of("\Component\Core\Parameters")])
                ] + $_parameters);
        }
        
        abstract protected function _fromCache(object $object, string $calledbacks, string $key, int $ttl = 3600);
       
        public function autoCallbacks(string $parameter) : void {
            if (isset($this->{$parameter})) {          
                foreach ($this->{$parameter}->restore() as $mapper => $callbacks) {
                    if (isset($this->library->{$mapper}) ) {
                        if (($object = ($this->library->{$mapper} === \get_class($this) ? $this : new $this->library->{$mapper})) && \method_exists($object, "callback")) {
                            try {
                                foreach ($callbacks as $label => $callback) {      
                                    try {
                                        $calledbacks = $this->getCallbacks($callback);
                                        if (\is_string($label)) {
                                            //caching responses or objects
                                            if (isset($this->cache->{$mapper}) && isset($this->cache->{$mapper}->{$label})) {
                                                if ($object instanceof \Component\Core\Controller) {
                                                    $this->controller->store([$mapper => [$label => $this->_fromCache($object, $calledbacks, $object->_search->unique([], $calledbacks), $this->cache->{$mapper}->{$label})]]);
                                                } else {
                                                    $object = $this->_fromCache($object, $calledbacks, $object->unique($object->diff(), $calledbacks), $this->cache->{$mapper}->{$label});
                                                    
                                                    $this->controller->store([$mapper => [$label => $object]]);
                                                }
                                            } else {
                                                $this->controller->store([$mapper => [$label => $object->callback($calledbacks)]]);
                                            }

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
                                            $object->callback($calledbacks);   
                                        }
                                    } catch (\UnexpectedValueException $ex) {
                                        throw new \LogicException(\sprintf("invalid value for parameter %s: %s", $ex->getMessage(), $ex->getPrevious()->getMessage()));
                                    } catch (\InvalidArgumentException $ex) {
                                        throw new \LogicException(\sprintf("%s parameter %s required", $label, $ex->getMessage()));
                                    } catch (\ErrorException | \TypeError | \ParseError | \Error $ex) {
                                        throw new \LogicException(\sprintf("%s %s", $label, $ex->getMessage()));
                                    }
                                }
                            } catch (\LogicException $ex) {
                                throw new \RuntimeException(\sprintf("%s/%s: %s", $parameter, $mapper, $ex->getMessage()));
                            }
                        }
                    }
                }
            }
        }
    }
}

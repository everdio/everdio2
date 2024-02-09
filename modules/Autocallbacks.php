<?php

namespace Modules {

    use \Component\Validation,
        \Component\Validator;

    abstract class Autocallbacks extends \Component\Core\Controller\Model\Http {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "_controller" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                    ] + $_parameters);
        }

        public function autoCallbacks(string $parameter): void {
            if (isset($this->{$parameter}) && isset($this->_library)) {
                foreach ($this->{$parameter}->restore() as $mapper => $callbacks) {
                    if (isset($this->_library->{$mapper})) {
                        if (($finder = ($this->_library->{$mapper} === \get_class($this) ? $this : new $this->_library->{$mapper}))) {
                            foreach ($callbacks as $label => $callback) {
                                try {
                                    if (\is_string($label)) {
                                        if (isset($this->request->{$this->debug})) {
                                            echo \sprintf("<!-- %s/%s/%s/%s -->\n", $parameter, $mapper, $label, \trim($callback, "{{}}"));
                                        }
                                        
                                        $this->_controller->store([$mapper => [$label => $finder->callback($this->getCallbacks($callback))]]);

                                        //continue if static value is controller value or break if static value is not controller value
                                        //[continue] or [break]
                                        if ((isset($this->continue->{$mapper}->{$label}) && $this->continue->{$mapper}->{$label} != $this->_controller->{$mapper}->{$label}) || (isset($this->break->{$mapper}->{$label}) && $this->break->{$mapper}->{$label} == $this->_controller->{$mapper}->{$label})) {
                                            unset($this->_controller->{$mapper}->{$label});
                                            return;
                                        }

                                        //is or isnot on callback value
                                        //[is] or [isnot]
                                        if ((isset($this->is->{$mapper}->{$label}) && isset($this->_controller->{$mapper}->{$label}) && $this->callback($this->is->{$mapper}->{$label}) != $this->_controller->{$mapper}->{$label}) || (isset($this->isnot->{$mapper}->{$label}) && isset($this->_controller->{$mapper}->{$label}) && $this->callback($this->isnot->{$mapper}->{$label}) == $this->_controller->{$mapper}->{$label})) {
                                            unset($this->_controller->{$mapper}->{$label});
                                            return;
                                        }

                                        //foreach loop
                                        //[foreach]
                                        if (isset($this->foreach->{$mapper}->{$label}) && (isset($this->_controller->{$mapper}->{$label}) && $this->_controller->{$mapper}->{$label} instanceof \Component\Core\Parameters)) {
                                            foreach ($this->_controller->{$mapper}->{$label}->restore() as $foreach) {
                                                $this->_controller->store([$mapper => [$label => $foreach]]);
                                                $this->callback($this->foreach->{$mapper}->{$label});
                                                unset($this->_controller->{$mapper}->{$label});
                                            }
                                        }
                                    } else {
                                        $finder->callback($this->getCallbacks($callback));
                                    }
                                } catch (\BadMethodCallException | \UnexpectedValueException | \InvalidArgumentException | \ErrorException | \ValueError | \TypeError | \ParseError | \Error $ex) {
                                    throw new \LogicException(\sprintf("%s/%s/%s/%s: %s", $parameter, $mapper, $label, \trim($callback, "{{}}"), $ex->getMessage()), 0, $ex);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}
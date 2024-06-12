<?php

namespace Component\Core\Controller\Model {

    trait Auto {

        public function auto(string $parameter): void {
            if (isset($this->_library) && isset($this->_auto) && isset($this->{$parameter})) {
                foreach ($this->{$parameter}->restore() as $mapper => $callbacks) {
                    if (isset($this->_library->{$mapper})) {
                        if (($finder = ($this->_library->{$mapper} === \get_class($this) ? $this : new $this->_library->{$mapper}))) {
                            foreach ($callbacks as $label => $callback) {
                                try {
                                    if (\is_string($label)) {
                                        if (isset($this->request->{$this->debug})) {
                                            echo \sprintf("<!-- %s/%s/%s/%s -->\n", $parameter, $mapper, $label, \str_replace(["{{", "}}"], ["{", "}"], $callback));
                                        }

                                        $this->_auto->store([$mapper => [$label => $finder->callback($this->getCallbacks($callback))]]);

                                        //continue if static value is controller value or break if static value is not controller value
                                        //[continue] or [break]
                                        if ((isset($this->continue->{$mapper}->{$label}) && $this->continue->{$mapper}->{$label} != $this->_auto->{$mapper}->{$label}) || (isset($this->break->{$mapper}->{$label}) && $this->break->{$mapper}->{$label} == $this->_auto->{$mapper}->{$label})) {
                                            unset($this->_auto->{$mapper}->{$label});
                                            return;
                                        }

                                        //is or isnot on callback value
                                        //[is] or [isnot]
                                        if ((isset($this->is->{$mapper}->{$label}) && isset($this->_auto->{$mapper}->{$label}) && $this->callback($this->is->{$mapper}->{$label}) != $this->_auto->{$mapper}->{$label}) || (isset($this->isnot->{$mapper}->{$label}) && isset($this->_auto->{$mapper}->{$label}) && $this->callback($this->isnot->{$mapper}->{$label}) == $this->_auto->{$mapper}->{$label})) {
                                            unset($this->_auto->{$mapper}->{$label});
                                            return;
                                        }

                                        //foreach loop
                                        //[foreach]
                                        if (isset($this->foreach->{$mapper}->{$label}) && (isset($this->_auto->{$mapper}->{$label}) && $this->_auto->{$mapper}->{$label} instanceof \Component\Core\Parameters)) {
                                            foreach ($this->_auto->{$mapper}->{$label}->restore() as $key => $foreach) {
                                                $this->_auto->store([$mapper => ["key" => $key, $label => $foreach]]);
                                                $this->callback($this->foreach->{$mapper}->{$label});
                                                unset($this->_auto->{$mapper}->{$label});
                                            }
                                        }
                                    } else {
                                        $finder->callback($this->getCallbacks($callback));
                                    }
                                } catch (\Exception $ex) {
                                    throw new \RuntimeException(\sprintf("%s/%s/%s/%s: %s", $parameter, $mapper, $label, \str_replace(["{{", "}}"], ["{", "}"], $callback), $ex->getMessage()), 0, $ex);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}
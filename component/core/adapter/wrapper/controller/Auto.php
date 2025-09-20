<?php

namespace Component\Core\Adapter\Wrapper\Controller {

    trait Auto {

        final public function auto(string $section): void {
            if (isset($this->{$section})) {
                foreach ($this->{$section}->restore() as $alias => $callbacks) {
                    if (isset($this->library->{$alias}) || ($this->library->{$alias} = \implode("\\", \array_map("\ucfirst", \explode("_", $alias))))) {
                        if (($finder = ($this->library->{$alias} === \get_class($this) ? $this : new $this->library->{$alias}))) {
                            foreach ($callbacks as $label => $callback) {
                                try {
                                    if (isset($this->request->{$this->debug})) {
                                        echo \sprintf("<!-- [%s]%s[%s] = %s -->\n", $section, $alias, $label, $this->getCallbacks($callback));
                                    }

                                    if (\is_string($label)) {
                                        $this->auto->store([$alias => [$label => $finder->callback($this->getCallbacks($callback))]]);

                                        //continue if static value is controller value or break if static value is not controller value
                                        //[continue] or [break]
                                        if ((isset($this->continue->{$alias}->{$label}) && $this->continue->{$alias}->{$label} != $this->auto->{$alias}->{$label}) || (isset($this->break->{$alias}->{$label}) && $this->break->{$alias}->{$label} == $this->auto->{$alias}->{$label})) {
                                            unset($this->auto->{$alias}->{$label});
                                            return;
                                        }

                                        //is or isnot on callback value
                                        //[is] or [isnot]
                                        if ((isset($this->is->{$alias}->{$label}) && isset($this->auto->{$alias}->{$label}) && $this->callback($this->is->{$alias}->{$label}) != $this->auto->{$alias}->{$label}) || (isset($this->isnot->{$alias}->{$label}) && isset($this->auto->{$alias}->{$label}) && $this->callback($this->isnot->{$alias}->{$label}) == $this->auto->{$alias}->{$label})) {
                                            unset($this->auto->{$alias}->{$label});
                                            return;
                                        }

                                        //foreach loop
                                        //[foreach]
                                        if (isset($this->foreach->{$alias}->{$label}) && (isset($this->auto->{$alias}->{$label}) && $this->auto->{$alias}->{$label} instanceof \Component\Core\Parameters)) {
                                            foreach ($this->auto->{$alias}->{$label}->restore() as $key => $foreach) {
                                                $this->auto->store([$alias => ["key" => $key, $label => $foreach]]);
                                                $this->callback($this->foreach->{$alias}->{$label});
                                                unset($this->auto->{$alias}->{$label});
                                            }
                                        }
                                    } else {
                                        $finder->callback($this->getCallbacks($callback));
                                    }
                                } catch (\Exception $ex) {
                                    throw new \RuntimeException(\sprintf("%s/%s/%s/%s %s", $section, $alias, $label, $callback, $ex->getMessage()));
                                }
                            }
                        }
                    }
                }
            }
        }

        final public function autocute(string $path): void {
            $this->dispatch(\dirname($path));
            $this->auto(\basename($path));
        }
    }

}
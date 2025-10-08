<?php

namespace Component\Core\Adapter\Wrapper\Controller {

    trait Auto {

        final public function auto(string $section): void {
            if (isset($this->{$section})) {
                if (isset($this->request->{$this->debug})) {
                    echo \sprintf("<!-- [%s] -->\n", $section);
                }

                foreach ($this->{$section}->restore() as $alias => $callbacks) {
                    if (isset($this->aliases->{$alias}) || ($this->aliases->{$alias} = \implode("\\", \explode("_", $alias)))) {
                        if (($finder = ($this->aliases->{$alias} === \get_class($this) ? $this : new $this->aliases->{$alias}))) {
                            foreach ($callbacks as $id => $callback) {
                                try {
                                    if (isset($this->request->{$this->debug})) {
                                        echo \sprintf("<!-- %s[%s] = %s -->\n", $alias, $id, $this->getCallbacks($callback));
                                    }

                                    if (\is_string($id)) {
                                        $this->auto->store([$alias => [$id => $finder->callback($this->getCallbacks($callback))]]);

                                        //continue if static value is controller value or break if static value is not controller value
                                        //[continue] or [break]
                                        if ((isset($this->continue->{$alias}->{$id}) && $this->continue->{$alias}->{$id} != $this->auto->{$alias}->{$id}) || (isset($this->break->{$alias}->{$id}) && $this->break->{$alias}->{$id} == $this->auto->{$alias}->{$id})) {
                                            unset($this->auto->{$alias}->{$id});
                                            return;
                                        }

                                        //is or isnot on callback value
                                        //[is] or [isnot]
                                        if ((isset($this->is->{$alias}->{$id}) && isset($this->auto->{$alias}->{$id}) && $this->callback($this->getCallbacks($this->is->{$alias}->{$id})) != $this->auto->{$alias}->{$id}) || (isset($this->isnot->{$alias}->{$id}) && isset($this->auto->{$alias}->{$id}) && $this->callback($this->getCallbacks($this->isnot->{$alias}->{$id})) == $this->auto->{$alias}->{$id})) {
                                            unset($this->auto->{$alias}->{$id});
                                            return;
                                        }

                                        //foreach loop
                                        //[foreach]
                                        if (isset($this->foreach->{$alias}->{$id}) && (isset($this->auto->{$alias}->{$id}) && $this->auto->{$alias}->{$id} instanceof \Component\Core\Parameters)) {
                                            foreach ($this->auto->{$alias}->{$id}->restore() as $key => $foreach) {
                                                unset($this->auto->{$alias}->{$id});
                                                $this->auto->store([$alias => ["key" => $key, $id => $foreach]]);
                                                $this->callback($this->foreach->{$alias}->{$id});
                                                unset($this->auto->{$alias}->{$id});
                                            }
                                        }
                                    } else {
                                        $finder->callback($this->getCallbacks($callback));
                                    }
                                } catch (\Exception $ex) {
                                    throw new \RuntimeException(\sprintf("%s/%s/%s/%s %s", $section, $alias, $id, $callback, $ex->getMessage()), 0, $ex);
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
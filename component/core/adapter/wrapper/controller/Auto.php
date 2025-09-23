<?php

namespace Component\Core\Adapter\Wrapper\Controller {

    trait Auto {

        final public function auto(string $section): void {
            if (isset($this->{$section})) {
                if (isset($this->request->{$this->debug})) {
                    echo \sprintf("<!-- [%s] -->\n", $section);
                }

                foreach ($this->{$section}->restore() as $alias => $callbacks) {
                    if (isset($this->library->{$alias}) || ($this->library->{$alias} = \implode("\\", \array_map("\ucfirst", \explode("_", $alias))))) {
                        if (($finder = ($this->library->{$alias} === \get_class($this) ? $this : new $this->library->{$alias}))) {
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
                                        if ((isset($this->is->{$alias}->{$id}) && isset($this->auto->{$alias}->{$id}) && $this->callback($this->is->{$alias}->{$id}) != $this->auto->{$alias}->{$id}) || (isset($this->isnot->{$alias}->{$id}) && isset($this->auto->{$alias}->{$id}) && $this->callback($this->isnot->{$alias}->{$id}) == $this->auto->{$alias}->{$id})) {
                                            unset($this->auto->{$alias}->{$id});
                                            return;
                                        }

                                        //foreach loop
                                        //[foreach]
                                        if (isset($this->foreach->{$alias}->{$id}) && (isset($this->auto->{$alias}->{$id}) && $this->auto->{$alias}->{$id} instanceof \Component\Core\Parameters)) {
                                            foreach ($this->auto->{$alias}->{$id}->restore() as $key => $foreach) {
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
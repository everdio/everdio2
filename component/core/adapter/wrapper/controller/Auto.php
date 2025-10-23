<?php

namespace Component\Core\Adapter\Wrapper\Controller {

    trait Auto {

        final public function auto(string $section, string $property = "auto"): void {
            if (isset($this->{$section})) {
                if (isset($this->request->{$this->debug})) {
                    echo \sprintf("<!-- [%s] -->\n", $section);
                }

                foreach ($this->{$section}->restore() as $alias => $callbacks) {
                    if (isset($this->aliases->{$alias}) || ($this->aliases->{$alias} = \implode("\\", \explode("_", $alias)))) {
                        if (($finder = ($this->aliases->{$alias} === \get_class($this) ? $this : new $this->aliases->{$alias}))) {
                            foreach ($callbacks as $id => $callback) {
                                if (isset($this->request->{$this->debug})) {
                                    echo \sprintf("<!-- %s[%s] = %s -->\n", $alias, $id, $this->getCallbacks($callback));
                                }

                                if (\is_string($id)) {
                                    $this->{$property}->store([$alias => [$id => $this->hydrate($finder->callback($this->getCallbacks($callback)))]]);
                                    
                                    //[continue] or [break] on value
                                    if (isset($this->{$property}->{$alias}->{$id}) && ((isset($this->continue->{$alias}->{$id}) && $this->continue->{$alias}->{$id} != $this->{$property}->{$alias}->{$id}) || (isset($this->break->{$alias}->{$id}) && $this->break->{$alias}->{$id} == $this->{$property}->{$alias}->{$id}))) {
                                        return;
                                    }

                                    //[is] or [isnot]
                                    if ((isset($this->is->{$alias}->{$id}) && isset($this->{$property}->{$alias}->{$id}) && $this->callback($this->getCallbacks($this->is->{$alias}->{$id})) != $this->{$property}->{$alias}->{$id}) || (isset($this->isnot->{$alias}->{$id}) && isset($this->{$property}->{$alias}->{$id}) && $this->callback($this->getCallbacks($this->isnot->{$alias}->{$id})) == $this->{$property}->{$alias}->{$id})) {
                                        return;
                                    }

                                    //[foreach]
                                    if (isset($this->foreach->{$alias}->{$id}) && (isset($this->{$property}->{$alias}->{$id}) && $this->{$property}->{$alias}->{$id} instanceof \Component\Core\Parameters)) {

                                        foreach ($this->{$property}->{$alias}->{$id}->restore() as $key => $foreach) {
                                            unset($this->{$property}->{$alias}->{$id});
                                            $this->{$property}->store([$alias => ["key" => $key, $id => $foreach]]);
                                            $this->callback($this->foreach->{$alias}->{$id});
                                            unset($this->{$property}->{$alias}->{$id});
                                            unset($this->{$property}->{$alias}->key);
                                        }
                                    }
                                } else {
                                    $finder->callback($this->getCallbacks($callback));
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
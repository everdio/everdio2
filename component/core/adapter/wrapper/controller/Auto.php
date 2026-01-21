<?php

namespace Component\Core\Adapter\Wrapper\Controller {

    trait Auto {

        final protected function auto(string $section, string $property = "auto", string $library = "aliases"): void {
            if (isset($this->{$section})) {
                if (isset($this->request->{$this->debug})) {
                    $this->echo(\sprintf("<!-- [%s] -->\n", $section));
                }

                foreach ($this->{$section}->restore() as $alias => $callbacks) {
                    if (!isset($this->{$library}->{$alias})) {
                        $this->{$library}->{$alias} = \implode("\\", \explode("_", $alias));
                    }
                    
                    if (!(new \ReflectionClass($this->{$library}->{$alias}))->getConstructor()->getNumberOfRequiredParameters()) {
                        
                        $object = ($this->{$library}->{$alias} === \get_class($this) ? $this : new $this->{$library}->{$alias});

                        foreach ($callbacks as $id => $callback) {
                            if (isset($this->request->{$this->debug}) && !(isset($this->hidden->{$alias}) && $this->hidden->{$alias} == $id)) {
                                $this->echo(\sprintf("<!-- %s[%s] = %s -->\n", $alias, $id, $this->getCallbacks($callback)));
                            }

                            if (\is_string($id)) {
                                $this->{$property}->store([$alias => [$id => $this->hydrate($object->callback($this->getCallbacks($callback)))]]);

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
                                $object->callback($this->getCallbacks($callback));
                            }
                        }
                    }
                }
            }
        }
    }

}
<?php

namespace Component\Core\Adapter {

    abstract class Mapper extends \Component\Core\Adapter {
        final public function reset(array $parameters = []): void {
            if (isset($this->mapping)) {
                $parameters = \array_merge($parameters, $this->mapping);
            }
            
            parent::reset($parameters);
        }

        final public function hasField(string $field): bool {
            return (bool) (isset($this->mapping) && \array_key_exists($field, $this->mapping));
        }

        final public function getField(string $parameter): string {
            if (isset($this->mapping) && $this->exists($parameter)) {
                return (string) \array_search($parameter, $this->mapping);
            }

            throw new \LogicException(\sprintf("unknown parameter %s", $parameter));
        }

        final public function __dry(): string {
            return (string) \sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->restore($this->mapping)));
        }
    }

}
<?php

namespace Component\Core\Adapter {

    abstract class Mapper extends \Component\Core\Adapter {

        use Threading;
        use Unix;

        final public function reset(array $parameters = []): self {
            if (isset($this->mapping)) {
                $parameters = \array_merge($parameters, $this->mapping);
            }

            return (object) parent::reset($parameters);
        }

        final public function hasField(string $field): bool {
            return (bool) (isset($this->mapping) && \array_key_exists($field, $this->mapping));
        }

        final public function getField(string $parameter): string {
            if (isset($this->mapping) && $this->hasParameter($parameter)) {
                return (string) \array_search($parameter, $this->mapping);
            }

            throw new \InvalidArgumentException($parameter);
        }

        final public function getPrimary(): array {
            if (isset($this->primary)) {
                return (array) $this->restore($this->primary);
            }
        }

        final public function getKeys(): array {
            if (isset($this->keys)) {
                return (array) $this->restore($this->keys);
            }
        }

        final public function getMapping(): array {
            if (isset($this->mapping)) {
                return (array) $this->restore($this->mapping);
            }
        }
    }

}
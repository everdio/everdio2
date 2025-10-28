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

        final public function isPrimary(string $parameter): bool {
            return (bool) (isset($this->primary) && \array_key_exists($parameter, $this->primary));
        }

        final public function isParent(string $parameter): bool {
            return (bool) (isset($this->parents) && \array_key_exists($parameter, $this->parents));
        }

        final public function isKey(string $parameter): bool {
            return (bool) (isset($this->keys) && \array_key_exists($parameter, $this->keys));
        }

        final public function getMapping(): array {
            return (array) $this->restore($this->mapping);
        }

        final public function getIdentifier(): array {
            return (array) $this->restore((isset($this->primary) ? (isset($this->keys) ? $this->primary + $this->keys : $this->primary) : $this->mapping));
        }

        final public function getHumanized(string $seperator = ", "): string {
            return (string) \implode($seperator, \array_filter($this->restore($this->mapping)));
        }
    }

}
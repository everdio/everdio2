<?php

namespace Component {

    abstract class Core {

        use Helpers,
            Dryer,
            Finder;

        public function __construct(private array $_parameters = []) {
            //nothing here
        }

        public function __isset(string $parameter): bool {
            return (bool) ($this->hasParameter($parameter) && $this->_parameters[$parameter]->isValid());
        }

        public function __unset(string $parameter) {
            if ($this->hasParameter($parameter)) {
                return (bool) $this->_parameters[$parameter]->set(false);
            }
        }

        public function __get(string $parameter): mixed {
            if ($this->hasParameter($parameter)) {
                try {
                    return $this->_parameters[$parameter]->execute();
                } catch (\ValueError $ex) {
                    throw new \UnexpectedValueException(\sprintf("INVALID_VALUE_FOR_PARAMETER %s->%s: %s", \get_class($this), $parameter, $ex->getMessage()), 0, $ex);
                }
            }

            throw new \InvalidArgumentException(\sprintf("INVALID_PARAMETER %s->%s", \get_class($this), $parameter));
        }

        public function __set(string $parameter, $value) {
            if ($this->hasParameter($parameter)) {
                return (bool) $this->_parameters[$parameter]->set((\is_array($value) && \is_array($this->_parameters[$parameter]->get()) ? \array_merge($this->_parameters[$parameter]->get(), $value) : $value));
            }

            throw new \InvalidArgumentException(\sprintf("INVALID_PARAMETER %s->%s", \get_class($this), $parameter));
        }

        public function __toString(): string {
            return (string) \get_class($this);
        }

        public function __invoke(string $parameter): Validation {
            if ($this->hasParameter($parameter)) {
                return (object) $this->getParameter($parameter);
            }

            throw new \InvalidArgumentException(\sprintf("INVALID_PARAMETER %s->%s", \get_class($this), $parameter));
        }

        final public function hasParameter(string $parameter): bool {
            return (bool) (\array_key_exists($parameter, $this->_parameters) && $this->_parameters[$parameter] instanceof Validation);
        }

        final public function addParameter(string $parameter, Validation $validation, ?bool $reset = null) {
            if (!$this->hasParameter($parameter) || $reset) {
                return (bool) $this->_parameters[$parameter] = $validation;
            }
        }

        final public function getParameter(string $parameter): Validation {
            if ($this->hasParameter($parameter)) {
                return (object) $this->_parameters[$parameter];
            }

            throw new \InvalidArgumentException(\sprintf("INVALID_PARAMETER %s->%s", \get_class($this), $parameter));
        }

        final public function remove(string $parameter): void {
            if ($this->hasParameter($parameter)) {
                unset($this->_parameters[$parameter]);
            }
        }

        final public function export(array $parameters = []): array {
            return (array) \array_intersect_key($this->_parameters, \array_flip($this->inter($parameters)));
        }

        final public function import(array $parameters): void {
            foreach ($parameters as $parameter => $validation) {
                $this->addParameter($parameter, $validation, true);
            }
        }

        final public function inter(array $parameters): array {
            return (array) \array_diff(\array_keys($this->_parameters), $this->diff($parameters));
        }

        final public function diff(array $parameters = []): array {
            return (array) \array_diff(\array_keys($this->_parameters), $parameters);
        }

        final public function sizeof(array $parameters = []): int {
            return (int) \sizeof($this->inter($parameters));
        }

        public function store(array $values): self {
            foreach ($values as $parameter => $value) {
                if ($this->hasParameter($parameter)) {
                    $this->{$parameter} = $value;
                }
            }

            return (object) $this;
        }

        public function restore(array $parameters = [], array $values = []): array {
            foreach ($this->inter($parameters) as $parameter) {
                if (isset($this->{$parameter})) {
                    $values[$parameter] = $this->{$parameter};
                }
            }

            return (array) $values;
        }

        public function querystring(array $parameters = []): string {
            return (string) \http_build_query($this->restore($parameters));
        }

        public function reset(array $parameters = []): self {
            return (object) $this->store(\array_fill_keys($this->inter($parameters), false));
        }

        final public function validations(array $parameters = [], array $validations = []): array {
            foreach ($this->inter($parameters) as $parameter) {
                $validations[$parameter] = (bool) isset($this->{$parameter});
            }

            return (array) $validations;
        }

        final public function unique(array $parameters = [], string $salt = "", string $algo = "sha256"): string {
            if (\in_array($algo, \hash_algos())) {
                return (string) \hash($algo, $this->querystring($parameters) . $salt);
            }

            throw new \InvalidArgumentException(\sprintf("INVALID_HASH_ALGORITHM %s->%s", \get_class($this), $algo));
        }

        final public function replace(string $content, array $parameters = [], string $replace = "{{%s}}", int $instances = 99): string {
            foreach ($this->restore($parameters) as $parameter => $value) {
                if (\is_float($value) || \is_numeric($value) || \is_string($value)) {
                    $content = \implode($value, \explode(\sprintf($replace, $parameter), $content, $instances));
                }
            }

            return (string) $content;
        }

        /*
          public function __clone() {
          return (object) \unserialize(\serialize($this));
          }
         * 
         */

        public function __dry(): string {
            return (string) $this->dehydrate($this->_parameters);
        }
    }

}
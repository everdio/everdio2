<?php

namespace Component {

    abstract class Core {

        use Helpers,
            Dryer,
            Finder;

        public function __construct(private array $_parameters = []) {
            foreach ($_parameters as $parameter => $validation) {
                $this->addParameter($parameter, $validation, true);
            }
        }

        public function __get(string $parameter): null|bool|int|float|string|array|object {
            if ($this->exists($parameter)) {
                try {
                    return $this->_parameters[$parameter]->execute();
                } catch (\ValueError $ex) {
                    throw new \UnexpectedValueException($parameter, 0, $ex);
                }
            }

            throw new \InvalidArgumentException($parameter);
        }

        public function __set(string $parameter, $value) {
            if ($this->exists($parameter)) {
                return (bool) $this->_parameters[$parameter]->setValue((\is_array($value) && \is_array($this->_parameters[$parameter]->getValue()) ? \array_merge($this->_parameters[$parameter]->getValue(), $value) : $value));
            }

            throw new \InvalidArgumentException($parameter);
        }

        public function __toString(): string {
            return (string) \get_class($this);
        }

        public function __invoke(string $parameter): Validation {
            if ($this->exists($parameter)) {
                return (object) $this->getParameter($parameter);
            }

            throw new \InvalidArgumentException($parameter);
        }

        public function __isset(string $parameter): bool {
            return (bool) ($this->exists($parameter) && $this->_parameters[$parameter]->isValid());
        }

        public function __unset(string $parameter) {
            if ($this->exists($parameter)) {
                return (bool) $this->_parameters[$parameter]->setValue(false);
            }

            throw new \InvalidArgumentException($parameter);
        }

        final public function exists(string $parameter): bool {
            return (bool) \array_key_exists($parameter, $this->_parameters);
        }

        final public function addParameter(string $parameter, Validation $validation, bool $reset = NULL) {
            if (!$this->exists($parameter) || $reset) {
                return (bool) $this->_parameters[$parameter] = $validation;
            }
        }

        final public function getParameter(string $parameter): Validation {
            if ($this->exists($parameter)) {
                return (object) $this->_parameters[$parameter];
            }

            throw new \InvalidArgumentException($parameter);
        }

        final public function setParameter(string $parameter, $value) {
            if ($this->exists($parameter)) {
                return $this->_parameters[$parameter]->setValue($value);
            }
        }

        final public function remove(string $parameter): void {
            if ($this->exists($parameter)) {
                unset($this->_parameters[$parameter]);
            }
        }

        final public function parameters(array $parameters = []): array {
            return (array) \array_intersect_key($this->_parameters, \array_flip($this->inter($parameters)));
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
                if ($this->exists($parameter)) {
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

        public function reset(array $parameters = []): void {
            $this->store(\array_fill_keys($this->inter($parameters), false));
        }

        final public function validations(array $parameters = [], array $validations = []): array {
            foreach ($this->inter($parameters) as $parameter) {
                $validations[$parameter] = (bool) isset($this->{$parameter});
            }

            return (array) $validations;
        }

        final public function unique(array $parameters = [], string $salt = "", string $algo = "sha512"): string {
            return (string) \hash($algo, $this->querystring($parameters) . $salt);
        }

        final public function replace(string $content, array $parameters = [], int $instances = 99, string $replace = "{{%s}}"): string {
            foreach ($this->restore($parameters) as $parameter => $value) {
                $content = \implode($value, \explode(\sprintf($replace, $parameter), $content, $instances));
            }

            return (string) $content;
        }
        
        final public function import(array $parameters):void {
            foreach ($parameters as $parameter => $validation) {
                if ($validation instanceof \Component\Validation) {
                    $this->addParameter($parameter, $validation, true);
                }
            }
        }

        public function __dry(): string {
            return (string) $this->dehydrate($this->_parameters);
        }
    }

}
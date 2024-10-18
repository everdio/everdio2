<?php

namespace Component {

    class Validation {

        use Dryer,
            Finder,
            Helpers;

        const NORMAL = "normal";
        const STRICT = "strict";

        protected $value = false;
        private $_validated = [], $_validators = [], $_types;

        public function __construct($value = false, array $validators = [], public string $validate = self::NORMAL) {
            $this->set($value);

            foreach ($validators as $validator) {
                if ($validator instanceof Validator) {
                    $key = \get_class($validator);

                    $this->_validators[$key] = $validator;
                    $this->_types[$key] = \strtolower($validator::TYPE);
                }
            }

            $this->validate = \strtolower($validate);
        }

        public function __toString(): string {
            return (string) \get_class($this);
        }

        public function __isset($type): bool {
            return (bool) \in_array(\strtolower($type), $this->_types);
        }

        public function __get(string $validator): \Component\Validator {
            if (\array_key_exists($validator, $this->_validators)) {
                return (object) $this->_validators[$validator];
            }

            throw new \LogicException(\sprintf("unknown validator %s", $validator));
        }
        
        public function set($value): void {
            $this->value = $this->hydrate($value);
        }

        public function get() {
            return $this->value;
        }        

        public function hasTypes(array $types, bool $match = false): bool {
            return (bool) ($match ? (\sizeof(\array_intersect($this->_types, $types)) === \sizeof($this->_types)) : \sizeof(\array_intersect($this->_types, $types)));
        }
        
        public function getTypes(): array {
            return (array) $this->_types;
        }

        public function validated(bool $validation = true): array {
            return (array) \array_intersect_key($this->_validators, \array_flip(\array_keys($this->_validated, $validation)));
        }

        public function isValid(): bool {
            foreach ($this->_validators as $key => $validator) {
                $this->_validated[$key] = $validator->execute($this->value);
            }

            return (bool) (\sizeof($this->_validated) && (($this->validate === self::NORMAL && \in_array(true, $this->_validated)) || ($this->validate === self::STRICT && !\in_array(false, $this->_validated))));
        }

        public function execute(): mixed {
            if ($this->isValid()) {
                return $this->value;
            }

            throw new \ValueError(\sprintf("value `%s` must be of type %s, %s given (%s)", $this->dehydrate($this->value), \implode("|", \array_intersect_key($this->_types, \array_flip(\array_keys($this->_validated, false)))), \gettype($this->value), $this->validate));
        }

        public function __dry(array $validators = []): string {
            foreach ($this->_validators as $validator) {
                $validators[] = $validator->__dry();
            }

            return (string) \sprintf("new \%s(%s, [%s], \Component\Validation::%s)", (string) $this, $this->dehydrate($this->value), \implode(", ", $validators), \strtoupper($this->validate));
        }

        public function __call(string $name, array $arguments): mixed {
            if (!\method_exists($this, $name)) {
                foreach ($this->_validators as $validator) {
                    if (\method_exists($validator, $name)) {
                        return \call_user_func_array([$validator, $name], $arguments);
                    }
                }

                throw new \InvalidArgumentException(\sprintf("unknown function name %s for validator(s): %s", $name, \implode(", ", $this->_validators)));
            }
        }
    }

}
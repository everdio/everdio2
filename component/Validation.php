<?php

namespace Component {

    class Validation {

        use Dryer,
            Finder,
            Helpers;

        const NORMAL = "NORMAL";
        const STRICT = "STRICT";

        private $_messages = [], $_validated = [], $_validators = [], $_types;

        public function __construct(protected $value = false, array $validators = [], public string $validate = self::NORMAL) {
            $this->setValue($value);

            foreach ($validators as $validator) {
                if ($validator instanceof Validator) {
                    $key = \get_class($validator);

                    $this->_validators[$key] = $validator;
                    $this->_messages[$key] = $validator::MESSAGE;
                    $this->_types[$key] = $validator::TYPE;
                }
            }

            $this->validate = \strtoupper($validate);
        }

        public function __toString(): string {
            return (string) \get_class($this);
        }

        public function __isset($type): bool {
            return (bool) \in_array($type, $this->_types);
        }

        public function __get(string $validator): \Component\Validator {
            if (\array_key_exists($validator, $this->_validators)) {
                return (object) $this->_validators[$validator];
            }

            throw new \LogicException(\sprintf("unknown validator %s", $validator));
        }

        public function has(array $types): bool {
            return (bool) \sizeof(\array_intersect($this->_types, $types));
        }

        public function match(array $types): bool {
            return (bool) (\sizeof(\array_intersect($this->_types, $types)) === \sizeof($this->_types));
        }

        public function setValue($value): void {
            $this->value = $this->hydrate($value);
        }

        public function getValue() {
            return $this->value;
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

        public function execute() {
            if ($this->isValid()) {
                return $this->value;
            }

            throw new \ValueError(sprintf("%s with %s (%s)", $this->substring($this->dehydrate($this->value), 0, 150), \implode(", ", \array_intersect_key($this->_messages, \array_flip(\array_keys($this->_validated, false)))), $this->validate));
        }

        public function __dry(array $validators = []): string {
            foreach ($this->_validators as $validator) {
                $validators[] = $validator->__dry();
            }

            return (string) \sprintf("new \%s(%s, [%s], \Component\Validation::%s)", (string) $this, $this->dehydrate($this->value), \implode(", ", $validators), \strtoupper($this->validate));
        }
    }

}
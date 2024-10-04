<?php

namespace Component\Validator\IsString {

    class InArray extends \Component\Validator\IsString {

        public function __construct(protected array $array = []) {}

        public function getArray(): array {
            return (array) $this->array;
        }

        public function execute($value): bool {
            return (bool) (parent::execute($value) && (\in_array($value, $this->array) || \array_key_exists($value, $this->array)));
        }

        public function __dry(): string {
            return (string) \sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->array));
        }
    }

}
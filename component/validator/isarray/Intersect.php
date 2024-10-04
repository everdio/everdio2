<?php

namespace Component\Validator\IsArray {

    class Intersect extends \Component\Validator\IsArray {

        public function __construct(protected array $array = []) {}

        public function getArray(): array {
            return (array) $this->array;
        }

        public function execute($value): bool {
            return (bool) (parent::execute($value) && (\sizeof($this->array) && \array_intersect($value, $this->array) === $value));
        }

        public function __dry(): string {
            return (string) \sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->array));
        }
    }

}


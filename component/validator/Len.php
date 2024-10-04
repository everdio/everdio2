<?php

namespace Component\Validator {

    abstract class Len extends \Component\Validator {

        const TYPE = "length";

        public function __construct(protected int $len = 0) {}

        public function getLen(): int {
            return (int) $this->len;
        }

        public function __dry(): string {
            return (string) \sprintf("new \%s(%s)", (string) $this, $this->len);
        }
    }

}


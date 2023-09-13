<?php

namespace Component {

    abstract class Validator {

        use Dryer,
            Finder;

        const TYPE = self::TYPE;
        const MESSAGE = self::MESSAGE;

        public function __toString() {
            return (string) \get_class($this);
        }

        public function __dry(): string {
            return (string) \sprintf("new \%s", (string) $this);
        }

        abstract public function execute($value): bool;
    }

}

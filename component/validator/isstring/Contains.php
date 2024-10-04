<?php

namespace Component\Validator\IsString {

    class Contains extends \Component\Validator\IsString {

        public function __construct(private array $_needles) {}

        public function execute($value): bool {
            return (bool) (parent::execute($value) && $this->hasInString($value, $this->_needles));
        }

        public function __dry(): string {
            return (string) \sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->_array));
        }
    }

}
<?php
namespace Components {
    abstract class Validator {
        use Dryer;
        public function __toString() {
            return (string) get_class($this);
        }
                
        public function __dry() : string {
            return (string) sprintf("new \%s", (string) $this);
        }

        abstract public function execute($value) : bool;
    }
}

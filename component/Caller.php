<?php

namespace Component {

    abstract class Caller {

        use Helpers,
            Dryer,
            Finder;

        protected $handle;

        public function __construct(private string $_call) {}

        final public function __invoke() {
            return $this->handle;
        }

        final public function __toString(): string {
            return (string) $this->_call;
        }

        final public function __call($name, $arguments): mixed {
            if (\function_exists(\sprintf($this->_call, $name))) {
                if (isset($this->handle)) {
                    \array_unshift($arguments, $this->handle);
                }

                return \call_user_func_array(\sprintf($this->_call, $name), $arguments);
            }

            throw new \BadFunctionCallException(\sprintf("UNKNOWN_FUNCTION_CALL %s (%s)", \sprintf($this->_call, $name), $this->dehydrate($arguments)));
        }

        public function __dry(): string {
            return (string) \sprintf("new %s", (string) $this);
        }
    }

}


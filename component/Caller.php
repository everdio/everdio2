<?php

namespace Component {

    abstract class Caller {

        use Helpers,
            Dryer,
            Finder;

        protected $handle;

        /**
         * 
         * @param string $_call
         */
        public function __construct(private string $_call) {
            
        }

        /**
         * 
         * @return type
         */
        final public function __invoke() {
            return $this->handle;
        }

        /**
         * 
         * @return string
         */
        final public function __toString(): string {
            return (string) $this->_call;
        }

        /**
         * 
         * @param type $name
         * @param type $arguments
         * @return mixed
         * @throws \BadFunctionCallException
         */
        final public function __call($name, $arguments): mixed {
            if (\function_exists(\sprintf($this->_call, $name))) {
                if (isset($this->handle)) {
                    \array_unshift($arguments, $this->handle);
                }

                return \call_user_func_array(\sprintf($this->_call, $name), $arguments);
            }

            throw new \BadFunctionCallException(\sprintf("UNKNOWN_FUNCTION_CALL: %s WITH_ARGUMENTS: %s", \sprintf($this->_call, $name), $this->dehydrate($arguments)));
        }

        /**
         * 
         * @return string
         */
        public function __dry(): string {
            return (string) \sprintf("new %s", (string) $this);
        }
    }

}


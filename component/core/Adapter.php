<?php

namespace Component\Core {

    abstract class Adapter extends \Component\Core {

        static private $_adapters = [];

        /*
         * returns the mapper adapter object
         */

        abstract protected function __init(): object;

        /*
         * in order to save resources we store the adapter in a static array
         */

        protected function getAdapter(): object {
            $key = $this->unique($this->adapter, "adapter", "crc32");

            if (!\array_key_exists($key, self::$_adapters)) {
                self::$_adapters[$key] = $this->__init();
            }

            return self::$_adapters[$key];
        }

        /*
         * redirects methods via the adapter
         */

        public function __call(string $method, array $arguments = []) {
            if (!\method_exists($this, $method)) {
                return \call_user_func_array([$this->getAdapter(), $method], $arguments);
            }
        }
    }

}
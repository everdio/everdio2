<?php

namespace Component\Core {

    abstract class Adapter extends \Component\Core {

        use Threading;

        static private $_adapters = [];

        /*
         * returns the mapper adapter object
         */

        abstract protected function addAdapter(): object;

        /*
         * in order to save resources we store the adapter in a static array
         */

        protected function getAdapter(): object {
            $key = $this->unique($this->adapter, "adapter", "crc32");

            if (!\array_key_exists($key, self::$_adapters)) {
                self::$_adapters[$key] = $this->addAdapter();
            }

            return self::$_adapters[$key];
        }

        /*
         * redirects methods via the adapter
         */

        public function __call(string $name, array $arguments = []) {
            if (!\method_exists($this, $name)) {
                return \call_user_func_array([$this->getAdapter(), $name], $arguments);
            }
        }
    }

}
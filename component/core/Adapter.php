<?php

namespace Component\Core {

    abstract class Adapter extends \Component\Core {

        static private $_adapters = [];

        /**
         * 
         * @return string
         */
        protected function getKey(): string {
            return (string) $this->unique($this->adapter, "adapter", "crc32");
        }

        /*
         * returns the mapper adapter object
         */

        abstract protected function addAdapter(): object;

        /*
         * in order to save resources we store the adapter in a static array
         * @param string $key
         * @return object
         */
        protected function getAdapter(string $key): object {
            if (!\array_key_exists($key, self::$_adapters)) {
                self::$_adapters[$key] = $this->addAdapter();
            }

            return self::$_adapters[$key];
        }

        /*
         * unsets the current adapter
         * @param string $key
         * @return void
         */
        protected function delAdapter(string $key): void {
            if (\array_key_exists($key, self::$_adapters)) {
                unset(self::$_adapters[$key]);
            }
        }

        /*
         * redirects method calls via the adapter
         * @param string $name
         * @param array $arguments
         * @return type
         */
        public function __call(string $name, array $arguments = []) {
            if (!\method_exists($this, $name)) {
                return \call_user_func_array([$this->getAdapter($this->getKey()), $name], $arguments);
            }
        }
    }

}
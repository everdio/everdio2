<?php
namespace Components {
    class Index {
        use Dryer;
        /*
         * private $key identifier
         */
        private $_key = false;

        /*
         * static private $_index array
         */
        static private $_index = [];

        public function __construct(string $_key) {
            $this->_key = $_key;
        }
        
        static public function construct(string $_key) : Index {
            return (object) new Index($_key);
        }

        public function restore() {
            return ($this->exists() ? self::$_index[$this->_key] : false);
        }

        public function store($value) {
            self::$_index[$this->_key] = $value;
        }
        
        public function exists() : bool {
            return (bool) array_key_exists($this->_key, self::$_index);
        }
        
        public function __dry() : string {
            return (string) sprintf("\Components\Index::construct(\"%s\")->restore()", $this->_key);
        }
    }
}
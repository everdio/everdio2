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

        public function restore() {
            if ($this->exists()) {
                return self::$_index[$this->_key];
            }        
        }

        public function store($value) {
            self::$_index[$this->_key] = $value;
        }
        
        public function exists() : bool {
            return (bool) array_key_exists($this->_key, self::$_index);
        }
        
        public function __dry() : string {
            return (string) sprintf("
            \$index = new \Components\Index(\"%s\");
            if (!\$index->exists()) {
                \$index->store(%s);
            }", $this->_key, $this->dehydrate($this->restore()));                    
        }
    }
}
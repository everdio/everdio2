<?php
namespace Component\Caller {
    abstract class File extends \Component\Caller {
        private $_file;
        public function __construct(string $_call, string $_file) {
            parent::__construct($_call);
            $this->_file = $_file;
        }        
        
        public function getPath() : string {
            return (string) $this->_file;
        }
    }
}
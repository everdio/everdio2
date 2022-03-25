<?php
namespace Component\Caller {
    abstract class File extends \Component\Caller {
        private $_file;
        public function __construct(string $_call, string $_file) {
            parent::__construct($_call);
            $this->_file = $_file;
        }        
        
        public function getExtension() : string {
            return (string) \pathinfo($this->_file, \PATHINFO_EXTENSION);
        }
        
        public function getBasename(string $extension = null) : string {
            return (string) \str_replace($extension, false, \pathinfo($this->_file, \PATHINFO_BASENAME));
        }        
        
        public function getPath() : string {
            return (string) $this->_file;
        }        
        
        public function delete() {
            return unlink($this->_file);
        }        
    }
}
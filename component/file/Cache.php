<?php
namespace Component\File {
    class Cache extends \Component\File {
        public function __construct($file) {
            try {
                $path = new \Component\Path(dirname($file));
                parent::__construct($path->getPath() . DIRECTORY_SEPARATOR . basename($file) . ".cache", "c+");
            } catch (\Exception $ex) {
                throw new \RuntimeException($ex->getMessage());
            }            
        }
        
        public function store($content, $permission = 0777) : int {
            try {
                return (int) parent::store(serialize($content), $permission);    
            } catch (\Exception $ex) {
                throw new \RuntimeException($ex->getMessage());
            }
        }
        
        public function restore($content = false) {
            try {
                return unserialize(parent::restore($content));   
            } catch (\Exception $ex) {
                throw new \RuntimeException($ex->getMessage());
            }
        }
    }
}


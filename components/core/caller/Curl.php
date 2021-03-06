<?php
namespace Components\Core\Caller {
    class Curl extends \Components\Core\Caller {
        public function __construct() {
            parent::__construct("curl");
            $this->resource = $this->init();
        }
        
        final public function execute() {
            if (($response = $this->exec()) === false) {                
                throw new \RuntimeException($this->error());
            }
            return (string) trim($response);
        }

        public function __destruct() {
            $this->close();
        }
    }
}
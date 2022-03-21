<?php
namespace Component\Core\Caller {
    class Curl extends \Component\Core\Caller {
        public function __construct() {
            parent::__construct("curl_");
            $this->resource = $this->init();
        }
        
        final public function execute() {
            if (($response = $this->exec()) === false) {          
                throw new \RuntimeException(sprintf("Curl: %s", $this->error()));
            }
            return (string) \trim($response);
        }

        public function __destruct() {
            $this->close();
        }
    }
}
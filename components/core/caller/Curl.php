<?php
namespace Components\Core\Caller {
    class Curl extends \Components\Core\Caller {
        public function __construct() {
            parent::__construct("curl");
            $this->resource = $this->init();
        }
        
        public function execute() : string {
            if (($response = $this->exec()) === false) {
                return (string) $this->error();
            }
            
            return (string) trim($response);
        }
        
        public function __destruct() {
            $this->close();
        }
    }
}
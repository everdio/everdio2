<?php

namespace Component\Caller\Curl {

    class Agent extends \Component\Caller\Curl {

        public function __construct(private $_cookie = "") {
            parent::__construct();

            $this->_cookie = \tempnam(\sys_get_temp_dir(), "_curl_agent_cookie");
            
            $agents = \explode(\PHP_EOL, \file_get_contents(__DIR__ . \DIRECTORY_SEPARATOR . "Agent.txt"));
            
            $this->setopt_array([
                \CURLOPT_USERAGENT => $agents[\array_rand($agents, 1)],
                \CURLOPT_SSL_VERIFYPEER => false,
                \CURLOPT_FOLLOWLOCATION => true,
                \CURLOPT_AUTOREFERER => true,
                \CURLOPT_RETURNTRANSFER => true,
                \CURLOPT_COOKIESESSION => true,
                \CURLOPT_COOKIEJAR => $this->_cookie,
                \CURLOPT_COOKIEFILE => $this->_cookie,
            ]);
        }

        public function __destruct() {
            parent::__destruct();

            \unlink($this->_cookie);
        }
    }

}
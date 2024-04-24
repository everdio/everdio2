<?php

namespace Component\Caller\Curl {

    class Client extends \Component\Caller\Curl {

        public function __construct(private $_cookie = "") {
            parent::__construct();

            $this->_cookie = \tempnam(\sys_get_temp_dir(), "everdio_caller_curl_api_cookie_");

            $this->setopt_array([
                \CURLOPT_SSL_VERIFYPEER => false,
                \CURLOPT_FOLLOWLOCATION => true,
                \CURLOPT_ENCODING => "",
                \CURLOPT_VERBOSE => false,
                \CURLOPT_HEADER => true,
                \CURLOPT_RETURNTRANSFER => true,
                \CURLOPT_COOKIEJAR => $this->_cookie,
                \CURLOPT_TIMEOUT => 9,
                \CURLOPT_COOKIEFILE => $this->_cookie,
            ]);
        }

        public function __destruct() {
            parent::__destruct();

            \unlink($this->_cookie);
        }
    }

}
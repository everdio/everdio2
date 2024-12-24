<?php

namespace Component\Caller\Curl {

    class Client extends \Component\Caller\Curl {

        public function __construct(string $agent, string $ip) {
            parent::__construct();

            $cookie = \tempnam(\sys_get_temp_dir(), "cookie_" . \md5($agent . $ip));
            
            $this->setopt_array([
                \CURLOPT_USERAGENT => $agent,
                \CURLOPT_SSL_VERIFYPEER => false,
                \CURLOPT_FOLLOWLOCATION => true,
                \CURLOPT_AUTOREFERER => true,
                \CURLOPT_VERBOSE => true,
                \CURLOPT_RETURNTRANSFER => true,
                \CURLOPT_TIMEOUT => 9,                
                \CURLOPT_COOKIESESSION => true,
                \CURLOPT_COOKIEJAR => $cookie,
                \CURLOPT_COOKIEFILE => $cookie,
            ]);
        }
    }

}
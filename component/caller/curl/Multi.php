<?php
namespace Component\Caller\Curl {
    class Multi extends \Component\Caller\Curl {
        public function __construct(string $caller = "curl_multi_%s") {
            parent::__construct($caller);
        }
        
        public function execute(bool $active = NULL) {
            do {
                $status = curl_multi_exec($this->getHandle(), $active);
                if ($active) {
                    $this->select();
                }
            } while ($active && $status == CURLM_OK);
        }
    }
}
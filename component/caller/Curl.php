<?php

namespace Component\Caller {

    class Curl extends \Component\Caller {

        public function __construct() {
            parent::__construct("curl_%s");
            $this->handle = $this->init();
        }

        final public function get($handle) {
            $this->setopt_array([
                \CURLOPT_FILE => $handle,
                \CURLOPT_CUSTOMREQUEST => "GET"]);
        }

        final public function put($handle, int $size = 0) {
            $this->setopt_array([
                \CURLOPT_INFILE => $handle,
                \CURLOPT_INFILESIZE => $size,
                \CURLOPT_PUT => true]);

            \fseek($handle, 0);
        }

        final public function post(string $content) {
            $this->setopt_array([
                \CURLOPT_POSTFIELDS => $content,
                \CURLOPT_INFILESIZE => \strlen($content),
                \CURLOPT_CUSTOMREQUEST => "POST"]);
        }

        final public function delete() {
            $this->setopt_array([
                \CURLOPT_CUSTOMREQUEST => "DELETE"]);
        }

        public function execute() {            
            if (($response = $this->exec()) === false) {
                throw new \ErrorException(\sprintf("CURL empty response, error: %s", $this->error()));
            }
            
            if (\in_array(($code = $this->getinfo(\CURLINFO_HTTP_CODE)), \range(400, 599), true)) {
                throw new \ErrorException(\sprintf("CURL response status code: %s", $code));
            }
            
            return (string) \trim($response);
        }

        public function __destruct() {
            $this->close();
        }
    }

}
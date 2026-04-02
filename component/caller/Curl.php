<?php

namespace Component\Caller {

    class Curl extends \Component\Caller {

        public function __construct(array $options = []) {
            parent::__construct("curl_%s");
            $this->handle = $this->init();
            $this->setopt_array($options);
        }

        final public function get($handle): void {
            $this->setopt_array([
                \CURLOPT_FILE => $handle,
                \CURLOPT_CUSTOMREQUEST => "GET"]);
        }

        final public function put($handle, int $size = 0): void {
            $this->setopt_array([
                \CURLOPT_INFILE => $handle,
                \CURLOPT_INFILESIZE => $size,
                \CURLOPT_CUSTOMREQUEST => "PUT"]);

            \fseek($handle, 0);
        }

        final public function post(string $content): void {
            $this->setopt_array([
                \CURLOPT_POSTFIELDS => $content,
                \CURLOPT_INFILESIZE => \strlen($content),
                \CURLOPT_CUSTOMREQUEST => "POST"]);
        }

        final public function delete(): void {
            $this->setopt_array([
                \CURLOPT_CUSTOMREQUEST => "DELETE"]);
        }

        final public function exists(string $url): bool {
            $this->setopt_array([
                \CURLOPT_NOBODY => true,
                \CURLOPT_FOLLOWLOCATION => true,
                \CURLOPT_URL => $url
            ]);

            return (bool) ($this->exec() && $this->getinfo(\CURLINFO_HTTP_CODE) === 200);
        }

        public function execute(): mixed {
            if (($response = $this->exec()) === false) {
                if (!$this->errno()) {
                    throw new \ErrorException("CURL_EMPTY_RESPONSE");
                } else {
                    throw new \ErrorException("CURL_ERROR: " . $this->error());
                }
            }

            return $response;
        }

        public function __destruct() {
            $this->close();
        }
    }

}
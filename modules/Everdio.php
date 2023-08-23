<?php

namespace Modules {

    use \Component\Core\Caller\Curl;

    trait Everdio {

        public function __construct() {
            parent::__construct([
                "key" => new Validation(false, [new Validator\IsString]),
                "host" => new Validation(false, [new Validator\IsString\IsUrl]),
                "endpoint" => new Validation(false, [new Validator\IsString]),
                "querystring" => new Validation(false, [new Validator\IsString])
            ]);
        }

        private function getResponse(\stdClass $response) {
            if (isset($response->status) && isset($response->body)) {
                if ($reponse->status === true) {
                    return (string) $response->body;
                } else {
                    throw new \RuntimeException($response->body);
                }
            }
        }

        final public function get() {
            $curl = new \Component\Core\Caller\Curl;
            $curl->setopt_array([\CURLOPT_URL => sprintf("%s?%s", $this->host . $this->endpoint, $this->querystring),
                \CURLOPT_HTTPHEADER => ["Authorization: " . $this->key],
                \CURLOPT_RETURNTRANSFER => true,
                \CURLOPT_CUSTOMREQUEST => "GET"]);

            return $this->getReponse(\json_decode($curl->execute()));
        }
    }

}
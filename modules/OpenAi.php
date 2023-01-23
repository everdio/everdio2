<?php
namespace Modules {
    trait OpenAi {
        protected function initialize() : object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_HTTPHEADER => ["Content-Type: application/json", \sprintf("Authorization: Bearer %s", $this->key)],
                \CURLOPT_RETURNTRANSFER => true
                ]); 

            return (object) $curl;
        }
     
        final public function post(string $content) {
            $this->setopt(\CURLOPT_URL, $this->url);
            $this->post($content);
            return (string) $this->execute();             
        }   
    }
}
<?php
namespace Modules {
    trait OpenAi {
        final protected function __init() : object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_URL => $this->url,
                \CURLOPT_HTTPHEADER => ["Content-Type: application/json", \sprintf("Authorization: Bearer %s", $this->key)],
                \CURLOPT_RETURNTRANSFER => true]); 
            return (object) $curl;
        }
        
        public function getResponse() : object {
            $this->post(\json_encode($this->restore(["model", "prompt", "temperature", "max_tokens", "top_p", "frequency_penalty", "presence_penalty", "n", "stream", "stop"])));
            return (object) \json_decode($this->execute());
        }
    }
}
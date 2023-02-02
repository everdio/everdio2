<?php
namespace Modules {
    trait OpenAi {
        protected function __innit() : object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_HTTPHEADER => ["Content-Type: application/json", \sprintf("Authorization: Bearer %s", $this->key)],
                \CURLOPT_RETURNTRANSFER => true]); 

            return (object) $curl;
        }
        
        public function getResponse(string $prompt) : array {
            $this->post(["prompt" => $prompt] + $this->restore(["model", "temperature", "max_tokens", "top_p", "frequency_penalty", "presence_penalty"]));
            return (array) \json_decode($this->execute(), true);
        }
    }
}
<?php

namespace Modules {

    use \Component\Validation,
        \Component\Validator;

    class OpenAi extends \Component\Core\Adapter {

        public function __construct(array $values = []) {
            parent::__construct([
                "url" => new Validation("https://api.openai.com/v1/chat/completions", [new Validator\IsString\IsUrl]),
                "key" => new Validation(false, [new Validator\IsString]),
                "adapter" => new Validation(["openai"], [new Validator\IsArray]),
                "model" => new Validation("gpt-3.5-turbo-16k", [new Validator\IsString]),
                "prompt" => new Validation(false, [new Validator\IsString]),
                "max_tokens" => new Validation(2048, [new Validator\IsInteger]),
                "temperature" => new Validation(0.5, [new Validator\IsFloat]),
                "top_p" => new Validation(1, [new Validator\IsFloat]),
                "messages" => new Validation([], [new Validator\IsArray]),
                "presence_penalty" => new Validation(0, [new Validator\IsFloat]),
                "frequency_penalty" => new Validation(0, [new Validator\IsFloat]),
                "n" => new Validation(1, [new Validator\IsInteger]),
                "stream" => new Validation(false, [new Validator\IsBool]),
                "stop" => new Validation(false, [new Validator\IsString]),
            ]);

            $this->store($values);
        }

        final protected function __init(): object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_URL => $this->url,
                \CURLOPT_HTTPHEADER => ["Content-Type: application/json", "Authorization: Bearer " . $this->key],
                \CURLOPT_RETURNTRANSFER => true]);
            return (object) $curl;
        }

        public function getResponse(): object {
            $this->post(\json_encode($this->restore(["model", "prompt", "messages", "temperature", "max_tokens", "top_p", "frequency_penalty", "presence_penalty", "n", "stream", "stop"])));
            return (object) \json_decode($this->execute());
        }
    }

}
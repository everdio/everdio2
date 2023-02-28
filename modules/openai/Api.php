<?php
namespace Modules\OpenAi {
    use \Component\Validation, \Component\Validator;     
    class Api extends \Component\Core\Adapter {
        use \Modules\OpenAi;
        public function __construct() {
            parent::__construct([
                "url" => new Validation("https://api.openai.com/v1/completions", [new Validator\IsString\IsUrl]),
                "key" => new Validation(false, [new Validator\IsString]),
                "adapter" => new Validation(["openai"], [new Validator\IsArray]),
                "model" => new Validation("text-davinci-003", [new Validator\IsString\InArray(["text-davinci-003", "text-curie-001", "text-babbage-001", "text-ada-001"])]),
                "prompt" => new Validation(false, [new Validator\IsString]),
                "max_tokens" => new Validation(2048, [new Validator\IsInteger]),
                "temperature" => new Validation(0.7, [new Validator\IsFloat]),
                "top_p" => new Validation(1, [new Validator\IsFloat]),
                "presence_penalty" => new Validation(0, [new Validator\IsFloat]),
                "frequency_penalty" => new Validation(0, [new Validator\IsFloat]),
                "n" => new Validation(1, [new Validator\IsInteger]),
                "stream" => new Validation(false, [new Validator\IsBool]),
                "stop" => new Validation(false, [new Validator\IsString]),
            ]);            
        }
    }
}
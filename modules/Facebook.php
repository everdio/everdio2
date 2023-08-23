<?php

namespace Modules {

    use \Component\Validation,
        \Component\Validator;

    abstract class Facebook extends \Component\Core\Adapter {

        public function __construct() {
            parent::__construct([
                "adapter" => new Validation(["page_id"], [new Validator\IsArray]),
                "host" => new Validation("https://graph.facebook.com/", [new Validator\IsString\IsUrl]),
                "page_id" => new Validation(false, [new Validator\IsString]),
                "endpoint" => new Validation(false, [new Validator\IsString]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject\Of("\Component\Core\Parameters")])
            ]);
        }

        protected function __init(): object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_FOLLOWLOCATION => true,
                \CURLOPT_RETURNTRANSFER => true,
                \CURLOPT_URL => \sprintf("%s/%s/%s?%s", $this->host, $this->page_id, $this->endpoint, $this->request->querystring())]);
            return (object) $curl;
        }
    }

}
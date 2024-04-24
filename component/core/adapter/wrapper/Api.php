<?php

namespace Component\Core\Adapter\Wrapper {

    use \Component\Validation,
        \Component\Validator;

    abstract class Api extends \Component\Core\Adapter\Wrapper {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "uri" => new Validation(false, [new Validator\IsString\IsUrl]),
                "request" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                "response" => new Validation(false, [new Validator\IsString, new Validator\IsNumeric]),
                "options" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject]),
                    ] + $_parameters);
        }

        final protected function __init(): object {
            //echo \sprintf("%s?%s", $this->uri, \urldecode($this->request->querystring()));
            $curl = new \Component\Caller\Curl\Client;
            $curl->setopt_array([
                \CURLOPT_URL => \sprintf("%s?%s", $this->uri, $this->request->querystring()),
                    ] + $this->options->restore());
            
            return (object) $curl;
        }
    }

}
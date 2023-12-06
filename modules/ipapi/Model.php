<?php

namespace Modules\IpApi {

    use \Component\Validation,
        \Component\Validator;

    class Model extends \Component\Core\Adapter\Model {

        use \Modules\IpApi;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "ipapi_url" => new Validation(false, [new Validator\IsString\IsUrl]),
                "ip" => new Validation(false, [new Validator\IsString])
            ] + $_parameters);

            $this->use = "\Modules\IpApi";
        }

        public function setup(): void {
            $xpath = new \DOMXPath($this->getDOMDocument());
            foreach ($xpath->query("//*") as $node) {
                $model = new \Modules\IpApi\Api\Model;
                $model->api = \sprintf("%s\%s", $this->namespace, $this->class);
                $model->adapter = $this->adapter;
                $model->node = $node;
                $model->namespace = \sprintf("%s\%s", $this->namespace, $this->class);
                $model->setup();
            }
        }

        public function __destruct() {
            unset($this->ip);
            parent::__destruct();
        }
    }

}
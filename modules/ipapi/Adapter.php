<?php

namespace Modules\IpApi {

    use \Component\Validation,
        \Component\Validator;

    class Adapter extends \Component\Core\Adapter\Model {

        use \Modules\IpApi;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "url" => new Validation(false, [new Validator\IsString\IsUrl]),
                "ip" => new Validation(false, [new Validator\IsString])
            ] + $_parameters);

            $this->use = "\Modules\IpApi";
        }

        public function setup(): void {
            $dom = $this->getDOMDocument();
            foreach ((new \DOMXPath($dom))->query("//*") as $node) {
                $model = new \Modules\IpApi\Model;
                $model->content = $dom->saveXML();
                $model->adapter = $this->adapter;
                $model->node = $node;
                $model->namespace = \sprintf("%s\%s", $this->namespace, $this->class);
                $model->setup();
                
                $model->api = \sprintf("%s\%s", $this->namespace, $this->class);
            }
        }

        public function __destruct() {
            unset($this->ip);
            
            parent::__destruct();
        }
    }

}
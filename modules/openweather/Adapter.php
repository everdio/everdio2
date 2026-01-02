<?php

namespace Modules\OpenWeather {

    use \Component\Validation,
        \Component\Validator;

    class Adapter extends \Component\Core\Adapter\Model {

        use \Modules\OpenWeather;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "url" => new Validation(false, [new Validator\IsString\IsUrl]),
                "appid" => new Validation(false, [new Validator\IsString]),
                "lang" => new Validation(false, [new Validator\IsString]),
                "units" => new Validation("metric", [new Validator\IsString\InArray(["standard", "metric", "imperial"])]),
                "mode" => new Validation("xml", [new Validator\IsString\InArray(["xml", "json"])]),
                "lat" => new Validation(false, [new Validator\IsFloat]),
                "lon" => new Validation(false, [new Validator\IsFloat])
                    ] + $_parameters);

            $this->use = "\Modules\OpenWeather";
        }

        public function setup(): void {
            $dom = $this->getDOMDocument();
            foreach ((new \DOMXPath($dom))->query("//*") as $node) {
                $model = new \Modules\OpenWeather\Model;
                $model->content = $dom->saveXML();
                $model->adapter = $this->adapter;
                $model->node = $node;
                $model->namespace = \sprintf("%s\%s", $this->namespace, $this->class);
                $model->api = \sprintf("%s\%s", $this->namespace, $this->class);
                $model->setup();
                $model->deploy();
            }
        }

        public function deploy(): void {
            unset($this->lon);
            unset($this->lat);
            unset($this->lang);
            
            parent::deploy();
        }
    }

}
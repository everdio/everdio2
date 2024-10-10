<?php

namespace Modules\OpenWeather {

    use \Component\Validation,
        \Component\Validator;

    class Model extends \Component\Core\Adapter\Model {

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
            $xpath = new \DOMXPath($this->getDOMDocument());
            foreach ($xpath->query("//*") as $node) {
                $model = new \Modules\OpenWeather\Api\Model;
                $model->api = \sprintf("%s\%s", $this->namespace, $this->class);
                $model->adapter = $this->adapter;
                $model->node = $node;
                $model->namespace = \sprintf("%s\%s", $this->namespace, $this->class);
                $model->setup();
            }
        }

        public function __destruct() {
            unset($this->lon);
            unset($this->lat);
            unset($this->lang);

            parent::__destruct();
        }
    }

}
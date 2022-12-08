<?php
namespace Modules\OpenWeather {
    use \Component\Validation, \Component\Validator;     
    final class Model extends \Component\Core\Adapter\Model {
        use \Modules\OpenWeather;
        public function __construct() {
            parent::__construct([
                "url" => new Validation(false, [new Validator\IsString]),
                "key" => new Validation(false, [new Validator\IsString]),
                "o" => new Validation("xml", [new Validator\IsString\InArray(["xml", "json"])]),
                "point" => new Validation(false, [new Validator\IsDouble])
            ]);
            
            $this->use = "\Modules\Bingmaps";            
        }

        public function setup() : void {         
            $xpath = new \DOMXPath($this->fetch());
            foreach ($xpath->query("//*") as $node) {
                $model = new \Modules\Bingmaps\Api\Model;
                $model->request = \sprintf("%s\%s", $this->namespace, $this->class);
                $model->node = $node;
                $model->namespace = \sprintf("%s\%s", $this->namespace, $this->class);
                $model->use = "\Modules\Bingmaps\Api";
                $model->setup();     
            }             

            unset ($this->lon);            
            unset ($this->lat);
            unset ($this->lang);
        }
    }
}
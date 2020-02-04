<?php
namespace Modules\OpenWeather {
    use \Components\Validation;
    use \Components\Validator;     
    class Model extends \Components\Core\Adapter\Model {
        public function __construct($key) {
            parent::__construct($key);            
            $this->add("url", new Validation(false, [new Validator\IsString]));
            $this->add("request", new Validation(false, [new Validator\IsString]));
            $this->add("appid", new Validation(false, [new Validator\IsString]));
            $this->add("lang", new Validation(false, [new Validator\IsInteger]));
            $this->add("units", new Validation(false, [new Validator\IsString]));
            $this->add("mode", new Validation("xml", [new Validator\IsString\InArray(["xml", "json"])]));
            $this->add("lat", new Validation(false, [new Validator\IsDouble]));
            $this->add("lon", new Validation(false, [new Validator\IsDouble]));
        }

        public function setup() {         
            $this->setopt_array([CURLOPT_URL => sprintf("%s?%s", $this->url, http_build_query($this->restore(array("appid", "lang", "units", "lat", "lon", "mode")))), CURLOPT_USERPWD]);
            $dom = new \DOMDOcument;
            $dom->loadXML($this->execute());            
            $xpath = new \DOMXPath($dom);
            foreach ($xpath->query("//*") as $node) {
                $model = new \Modules\Node\Model(strtolower($this->request));
                $model->node = $node;
                $model->root = $this->root;
                $model->namespace = sprintf("%s\Response", $this->namespace);
                $model->setup();        
            }             

            unset ($this->lon);            
            unset ($this->lat);
            unset ($this->request);
        }
    }
}
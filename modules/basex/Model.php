<?php
namespace Modules\BaseX {
    use \Component\Validation, \Component\Validator;     
    final class Model extends \Component\Core\Adapter\Model {
        use \Modules\BaseX;
        public function __construct() {
            parent::__construct([
                "username" => new Validation(false, [new Validator\IsString]),
                "password" => new Validation(false, [new Validator\IsString]),
                "database" => new Validation(false, [new Validator\IsString]),
                "host" => new Validation(false, [new Validator\IsString\IsUrl]),
                "query" => new Validation(false, [new Validator\IsString\IsXPath]),
                "root" => new Validation(false, [new Validator\IsString]),
                "keys" => new Validation(false, [new Validator\IsArray])
            ]);
            
            $this->use = "\Modules\BaseX";
            $this->class = "Api";
            $this->model = __DIR__ . \DIRECTORY_SEPARATOR . "Model.tpl";
        }   
 
        public function setup() : void {
            $xpath = new \DOMXPath($this->fetchDOM($this->query));
            foreach ($xpath->query("//*") as $node) {
                $model = new \Modules\BaseX\Api\Model;
                if (isset($this->keys)) {
                    $model->primary = $this->keys;
                }
                $model->api = \sprintf("%s\%s", $this->namespace, $this->class);
                $model->node = $node;
                $model->namespace = \sprintf("%s\%s", $this->namespace, $this->class);
                $model->use = "\Modules\BaseX\Api";
                $model->setup();
            }                
        }
        
        public function __destruct() {
            $this->remove("query");
            parent::__destruct();
        }        
    }
}
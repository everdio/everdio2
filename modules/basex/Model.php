<?php
namespace Modules\BaseX {
    use \Components\Validation;
    use \Components\Validator;     
    class Model extends \Components\Core\Adapter\Mapper\Model {
        public function __construct($key) {
            parent::__construct($key);
            $this->add("username", new Validation(false, [new Validator\IsString]));
            $this->add("password", new Validation(false, [new Validator\IsString]));
            $this->add("host", new Validation(false, [new Validator\IsString\IsUrl]));
            $this->add("query", new Validation(false, [new Validator\IsString\IsPath]));
            $this->add("filters", new Validation(false, [new Validator\IsArray]));
            $this->add("keys", new Validation(false, [new Validator\IsArray]));
            $this->add("fields", new Validation(false, [new Validator\IsArray]));
            $this->add("request", new Validation(false, [new Validator\IsString]));
            $this->add("responses", new Validation(false, [new Validator\IsArray]));
        }
        
        public function setup() {            
            $this->setopt_array([CURLOPT_URL => sprintf("%s?query=%s", $this->host, $this->query), CURLOPT_USERPWD => sprintf("%s:%s", $this->username, $this->password)]);
            
            $dom = new \DOMDOcument;
            $dom->loadXML(sprintf("<%s>%s</%s>", strtolower($this->request), $this->execute(), strtolower($this->request)));            
            
            $xpath = new \DOMXPath($dom);
            foreach ($xpath->query("//*") as $node) {
                $model = new \Modules\Node\Model(strtolower($this->request));
                $model->node = $node;
                $model->root = $this->root;
                $model->namespace = sprintf("%s\Response", $this->namespace);
                $model->setup();        
                
                if (in_array($node->getNodePath(), $this->responses)) {
                    $this->filters = [array_search($node->getNodePath(), $this->responses) => sprintf("%s\%s", $model->namespace, $model->class)];
                }
            }             
        }

        public function __destruct() {
            if (isset($this->fields)) {
                $keys = [];
                foreach ($this->fields as $key => $samples) {
                    foreach ($samples as $field => $sample) {
                        $parameter = new \Components\Core\Parameter($field);
                        $parameter->mandatory = true;
                        $parameter->length = false;
                        $parameter->sample = $sample;
                        $this->add($parameter->parameter, $parameter->getValidation($parameter->getValidators()));
                        $this->mapping = [$field => $parameter->parameter];
                        $keys[$key][] = $parameter->parameter;
                    }
                }     
                $this->keys = $keys;
                $this->remove("fields");
                $this->remove("responses");
                $this->remove("request");
                unset($this->query);
                parent::__destruct();                
            }
        }
    }
}
<?php

namespace Modules\BaseX {

    use \Component\Validation,
        \Component\Validator;

    class Adapter extends \Component\Core\Adapter\Model {

        use \Modules\BaseX;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "username" => new Validation(false, [new Validator\IsString]),
                "password" => new Validation(false, [new Validator\IsString]),
                "database" => new Validation(false, [new Validator\IsString]),
                "host" => new Validation(false, [new Validator\IsString\IsUrl]),
                "log" => new Validation(false, [new Validator\IsString]),
                "query" => new Validation(false, [new Validator\IsString]),
                "root" => new Validation(false, [new Validator\IsString]),
                "keys" => new Validation(false, [new Validator\IsArray])
                    ] + $_parameters);

            $this->use = "\Modules\BaseX";
        }

        public function setup(): void {
            $dom = new \DOMDocument("1.0", "UTF-8");
            $dom->loadXML(\sprintf("<%s>%s</%s>", $this->root, $this->getResponse($this->query), $this->root), \LIBXML_HTML_NODEFDTD | \LIBXML_HTML_NOIMPLIED | \LIBXML_NOCDATA | \LIBXML_NOERROR | \LIBXML_NONET | \LIBXML_NOWARNING | \LIBXML_NSCLEAN | \LIBXML_COMPACT | \LIBXML_NOBLANKS | \LIBXML_NOENT);

            $xpath = new \DOMXPath($dom);
            foreach ($xpath->query("//*") as $node) {
                $model = new \Modules\BaseX\Model;
                $model->content = $dom->saveXML();
                $model->adapter = $this->adapter;
                $model->namespace = \sprintf("%s\%s", $this->namespace, $this->class);
                $model->node = $node;
                $model->setup();
                
                if (isset($model->mapping)) {
                    $model->primary = \array_intersect_key($this->keys, $model->mapping);
                }
                
                $model->api = \sprintf("%s\%s", $this->namespace, $this->class);
                
                $model->create();
            }
        }

        public function __destruct() {
            $this->remove("query");
            $this->remove("keys");
            
            parent::__destruct();
        }
    }

}

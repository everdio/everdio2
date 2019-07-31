<?php
namespace Modules\Node {    
    class Library extends \Components\Core\Mapping\Library {
        public function __construct(\DOMDocument $dom) {
            parent::__construct($dom);
            $this->extend = "\Modules\Node";            
        }
        
        public function setup() {
            foreach ($this->execute("//*") as $node) {
                $model = new \Modules\Node\Model($this->instance);
                $model->root = $this->root;
                $model->node = $node;
                $model->namespace = $this->getNamespace();
                $model->extend = $this->getExtend();
                $model->setup();   
                
                $this->mappers = [sprintf("%s\%s", $model->namespace, $model->mapper)];
            }             
        }
    }
}
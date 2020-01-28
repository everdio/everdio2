<?php
namespace Modules\Node {
    use Components\Validator;
    use Components\Validation;
    final class Model extends \Components\Core\Adapter\Mapper\Model {
        public function __construct($key) {
            parent::__construct($key);
            $this->extends = "\Modules\Node";
            $this->add("node", new Validation(false, [new Validator\IsObject\Of("\DOMElement")]));
            $this->add("tag", new Validation(false, array(new Validator\IsString)));
            $this->add("path", new Validation(false, array(new Validator\IsString\IsPath)));
            $this->add("current", new Validation(false, array(new Validator\IsString)));
            $this->add("parent", new Validation(false, array(new Validator\IsString)));           
        }
        
        public function setup() {
            $this->path = preg_replace('/\[(.*?)\]/', false, $this->node->getNodePath());  
            $this->class = ucFirst($this->node->tagName);
            $this->tag = ucFirst($this->node->tagName);
            
            if (isset($this->namespace) && $this->node->parentNode->nodeName !== "#document") {
                $this->namespace = $this->namespace . implode("\\", array_map("ucFirst", explode(DIRECTORY_SEPARATOR, dirname($this->path))));
            }    
            
            if ($this->node->hasAttributes()) {
                foreach ($this->node->attributes as $attribute) {
                    $parameter = new \Components\Core\Parameter($attribute->nodeName);
                    $parameter->mandatory = true;

                    if (!empty(trim($attribute->value))) {
                        $parameter->sample = trim($attribute->value);
                    }

                    $this->add($parameter->parameter, $parameter->getValidation($parameter->getValidators()));
                    $this->mapping = array($attribute->nodeName => $parameter->parameter);
                }            
            }
            
            $this->add($this->tag, new Validation(false, array(new Validator\IsInteger, new Validator\IsString)));            
            $this->remove("node");
        }
    }
}

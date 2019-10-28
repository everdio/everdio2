<?php
namespace Modules\Node {
    use Components\Validator;
    use Components\Validation;
    final class Model extends \Components\Core\Mapper\Model {
        public function __construct(\Components\Index $index) {
            parent::__construct($index);
            $this->extends = "\Modules\Node";
            $this->add("node", new Validation(false, [new Validator\IsObject\Of("\DOMElement")]));
            $this->add("tag", new Validation(false, array(new Validator\IsString)));
            $this->add("path", new Validation(false, array(new Validator\IsString\IsPath)));
            $this->add("current", new Validation(false, array(new Validator\IsString)));
            $this->add("parent", new Validation(false, array(new Validator\IsString)));
            $this->add("Text", new Validation(false, array(new Validator\IsNumeric, new Validator\IsString)));
        }
        
        public function setup() {
            $this->path = preg_replace('/\[(.*?)\]/', false, $this->node->getNodePath());  
            $this->class = ucFirst($this->node->tagName);
            $this->tag = $this->node->tagName;
            
            if ($this->node->parentNode->nodeName !== "#document") {
                $this->namespace = $this->namespace . implode("\\", array_map("ucFirst", explode(DIRECTORY_SEPARATOR, dirname($this->path))));
            }            
            
            foreach ($this->node->attributes as $attribute) {
                if (!$this->hasField($attribute->nodeName)) {
                    $attr = new Attribute;
                    $attr->parameter = $this->labelize($attribute->nodeName);
                    $attr->field = $attribute->nodeName;
                    $attr->mandatory = true;
                    $attr->length = 255;
                    $attr->default = false;                    
                    $this->add($attr->parameter, $attr->getValidation($attr->getValidators(), Validation::NORMAL));
                    $this->mapping = array($attr->field => $attr->parameter);
                }
            }            
            
            $this->remove("node");
        }
    }
}

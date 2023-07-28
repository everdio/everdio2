<?php
namespace Modules\Node {
    use Component\Validation, Component\Validator;
    abstract class Model extends \Component\Core\Adapter\Mapper\Model {
        use \Modules\Node;
        public function __construct(array $parameters = []) {
            parent::__construct([
                "document" => new Validation(false, [new Validator\IsString\IsFile, new Validator\IsString\IsUrl, new Validator\IsString]),
                "node" => new Validation(false, [new Validator\IsObject\Of("\DOMElement")]),
                "tag" => new Validation(false, array(new Validator\IsString)),
                "path" => new Validation(false, array(new Validator\IsString\IsPath)),                
                "parent" => new Validation(false, array(new Validator\IsString)),
                "index" => new Validation(false, array(new Validator\IsString))
            ] + $parameters);
        }
        
        public function setup() : void {
            $this->path = \preg_replace('/\[(.*?)\]/', false, $this->node->getNodePath());  
            $this->label = \ucFirst(\strtolower($this->node->tagName));
            $this->class = \ucFirst(\strtolower($this->node->tagName));
            $this->tag = $this->node->tagName;     
            
            if (isset($this->namespace) && $this->node->parentNode->nodeName !== "#document") {
                $this->namespace = $this->namespace . \implode("\\", \array_map("ucFirst", \explode(\DIRECTORY_SEPARATOR, \dirname($this->path))));
                $this->parents = ["parent" => $this->namespace];
            }                
            
            if ($this->node->hasAttributes()) {
                foreach ($this->node->attributes as $attribute) {
                    $parameter = new \Component\Validation\Parameter((!empty(\trim($attribute->value)) ? \trim($attribute->value) : false), false, true);
                    $this->addParameter($this->getLabelized($attribute->nodeName), $parameter->getValidation($parameter->getValidators()));
                    $this->mapping = [$attribute->nodeName => $this->getLabelized($attribute->nodeName)];
                }            
            }
            
            $this->mapping = [\strtolower($this->label) => $this->label];            
            
            if ($this->node->hasChildNodes() && $this->node->childNodes->length === 1 && $this->node->firstChild->nodeType === \XML_TEXT_NODE) {
                $parameter = new \Component\Validation\Parameter($this->node->firstChild->nodeValue, false, true);
                $this->addParameter($this->label, $parameter->getValidation($parameter->getValidators()));
            }
            
            $this->remove("node");
        }
    }
}

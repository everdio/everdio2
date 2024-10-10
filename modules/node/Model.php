<?php

namespace Modules\Node {

    use Component\Validation,
        Component\Validator;

    abstract class Model extends \Component\Core\Adapter\Mapper\Model {

        use \Modules\Node;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "node" => new Validation(false, [new Validator\IsObject\Of("\DOMElement")]),
                "tag" => new Validation(false, array(new Validator\IsString)),
                "path" => new Validation(false, array(new Validator\IsString\IsPath)),
                "parent" => new Validation(false, array(new Validator\IsString)),
                "index" => new Validation(false, array(new Validator\IsString))
                    ] + $_parameters);
        }
        
        public function setup(): void {
            $this->path = \preg_replace('/\[(.*?)\]/', false, $this->node->getNodePath());
            $this->label = \ucFirst(\strtolower($this->node->tagName));
            $this->class = $this->label;
            $this->tag = $this->node->tagName;

            if (isset($this->namespace) && $this->node->parentNode->nodeName !== "#document") {
                $this->namespace = $this->namespace . \implode("\\", \array_map("ucFirst", \explode(\DIRECTORY_SEPARATOR, \dirname($this->path))));
                $this->parents = ["parent" => $this->namespace];
            }

            if ($this->node->hasAttributes()) {
                foreach ($this->node->attributes as $attribute) {
                    $validators = [];
                    
                    foreach ($this->query(\sprintf("%s/@%s", $this->path, $attribute->nodeName)) as $value) {
                        $validation = (new \Component\Validation\Parameter(\trim($value->value), false, true));
                        $validators = $validation->getValidators($validators);
                    }
                    
                    $parameter = $this->beautify($attribute->nodeName);
                    
                    $this->addParameter($parameter, $validation->getValidation($validators));
                    
                    $this->mapping = [$attribute->nodeName => $parameter];
                }
            }

            $this->mapping = [\strtolower($this->label) => $this->label];

            if ($this->node->hasChildNodes() && $this->node->childNodes->length === 1 && $this->node->firstChild->nodeType === \XML_TEXT_NODE) {
                $validation = new \Component\Validation\Parameter($this->node->firstChild->nodeValue, false, true);
                $this->addParameter($this->label, $validation->getValidation());
            }

            $this->remove("node");
        }
    }

}

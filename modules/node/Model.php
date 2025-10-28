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
                "index" => new Validation(false, array(new Validator\IsString)),
                "Index" => new Validation(false, array(new Validator\IsString)),
                "parent" => new Validation(false, array(new Validator\IsString)),
                "Parent" => new Validation(false, array(new Validator\IsString))
                    ] + $_parameters);
        }
        
        public function setup(): void {
            $this->path = \preg_replace('/\[(.*?)\]/', false, $this->node->getNodePath());
            $this->label = \ucFirst(\strtolower($this->node->tagName));
            $this->class = $this->label;
            $this->tag = $this->node->tagName;
            $this->primary = ["Index"];
            $this->mapping = ["index" => "Index"];

            if (isset($this->namespace) && $this->node->parentNode->nodeName !== "#document") {
                $this->namespace = $this->namespace . \implode("\\", \array_map("ucFirst", \explode(\DIRECTORY_SEPARATOR, \dirname($this->path))));
                $this->parents = ["parent" => $this->namespace, "Parent" => $this->namespace];
                $this->primary = ["Parent"];
                $this->keys = ["Parent" => "Index"];
                $this->mapping = ["parent" => "Parent"];
            }
            
            $parameters = [];
            
            foreach ($this->query(\sprintf("%s/@*", $this->path)) as $attribute) {    
                $parameters += [$attribute->nodeName => (new \Component\Validation\Parameter($attribute->value, false, true))->getValidators()];
            }
            
            foreach ($parameters as $attribute => $validators) {
                $this->mapping = [$attribute => ($parameter = $this->beautify($attribute))];                
                $this->addParameter($parameter, new \Component\Validation(false, $validators));
            }

            /*
            if ($this->node->hasAttributes()) {
                foreach ($this->node->attributes as $attribute) {
                    $validators = [];
                    
                    $validation = (new \Component\Validation\Parameter(\trim($attribute->value), false, true));

                    foreach ($this->query(\sprintf("%s/@%s", $this->path, $attribute->nodeName)) as $value) {
                        $validation = new \Component\Validation\Parameter(\trim($value->value), false, true);
                        $validators = $validation->getValidators($validators);
                    }

                    $parameter = $this->beautify($attribute->nodeName);
                    $this->addParameter($parameter, $validation->getValidation($validators));
                    $this->mapping = [$attribute->nodeName => $parameter];
                }
            }
             * 
             */
            
            $parameter = [];
            
            foreach ($this->query(\sprintf("%s/text()", $this->path)) as $node) {
                $parameter = (new \Component\Validation\Parameter(\trim($node->nodeValue), false, true))->getValidators($parameter);
            }
            
            $this->addParameter($this->label, (new \Component\Validation\Parameter(false, false, true))->getValidation($parameter));
            $this->mapping = [\strtolower($this->label) => $this->label];

            $this->remove("node");
        }
    }

}

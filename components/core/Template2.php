<?php
namespace Components\Core {
    use \Components\Validation;
    use \Components\Validator;    
    class Template2 extends \Components\Core {
        public function __construct(string $template, string $tag = "@", array $parameters =[], array $matches = []) {
            preg_match_all("/" . $tag . "(.*?)" . $tag . "/", $template, $matches);
            
            parent::__construct([
                "template" => new Validation($template, [new Validator\IsString]),
                "tag" => new Validation($tag, [new Validator\Len\Smaller(1)]),
                "parameters" => new Validation($matches[1], [new Validator\IsArray]),
                "values" => new Validation(new \Components\Core\Parameters, [new Validator\IsObject\Of("\Components\Core\Parameters")])
                
            ] + $parameters);
        }
        
        public function __set(string $field, $value) : bool {
            $parameter = new \Components\Validation\Parameter($field, $value, (bool) $value, true);
            return (bool) $this->values->add((string) $parameter, $parameter->getValidation($parameter->getValidators()));
        }
        
        public function display() {
            $template = $this->template;
            foreach ($this->values->restore() as $parameter => $value) {
                $template = str_replace($this->tag . $parameter . $this->tag, $value, $template);
            }
            return (string) $template;
        }
    }
}
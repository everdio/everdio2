<?php
namespace Components\Core {
    use \Components\Validator;
    class Template extends \Components\Core {
        public function __set(string $parameter, $value) : bool {
            return (bool) $this->add($parameter, new \Components\Validation($value, array(new Validator\IsString, new Validator\IsInteger, new Validator\IsEmpty)));
        }
        
        final public function display(string $template, string $enclosure = "@") : string { 
            foreach ($this->restore() as $parameter => $value) {
                $template = str_replace($enclosure . $parameter . $enclosure, $value, $template);
            }
            
            return (string) $template;
        }
    }
}
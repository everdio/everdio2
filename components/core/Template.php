<?php
namespace Components\Core {
    use \Components\Validation;
    use \Components\Validator;
    
    class Template extends \Components\Core {
        public function __set(string $parameter, $value) : bool {
            return (bool) $this->add($parameter, new Validation($value, array(new Validator\IsString, new Validator\IsInteger, new Validator\IsEmpty)));
        }
        
        public function display(string $content, string $enclosure = "@") : string { 
            foreach ($this->restore() as $parameter => $value) {
                $content = str_replace($enclosure . $parameter . $enclosure, $value, $content);
            }
            
            return (string) $content;
        }
    }
}
<?php
namespace Components\Core {
    class Template extends \Components\Core {
        public function __set(string $field, $value) : bool {
            $parameter = new \Components\Validation\Parameter($field, $value, $value, true);
            return (bool) $this->add((string) $parameter, $parameter->getValidation($parameter->getValidators()));
        }

        final public function display(string $template, string $enclosure = "@") : string { 
            foreach ($this->restore($this->diff()) as $parameter => $value) {
                $template = str_replace($enclosure . $parameter . $enclosure, $value, $template);
            }
            
            return (string) $template;
        }
    }
}
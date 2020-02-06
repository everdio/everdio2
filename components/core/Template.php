<?php
namespace Components\Core {
    class Template extends \Components\Core {
        public function __set(string $field, $value) : bool {
            $parameter = new Parameter($field);
            $parameter->sample = $value;
            $parameter->default = $value;
            $parameter->mandatory = true;
            return (bool) $this->add($parameter->parameter, $parameter->getValidation($parameter->getValidators()));
        }

        final public function display(string $template, string $enclosure = "@") : string { 
            foreach ($this->restore($this->parameters()) as $parameter => $value) {
                $template = str_replace($enclosure . $parameter . $enclosure, $value, $template);
            }
            
            return (string) $template;
        }
    }
}
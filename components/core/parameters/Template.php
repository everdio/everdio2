<?php
namespace Components\Core\Parameters {
    class Template extends \Components\Core\Parameters {
        public function import(string $querystring, array $cores = []) {
            parse_str($querystring, $cores);
            foreach ($cores as $core => $values) {
                foreach ($values as $parameter => $value) {
                    $this->{sprintf("%s[%s]", $core, $parameter)} = $value;
                }
            }
        }
        
        final public function display(string $template, string $replace = "{{%s}}", string $regex = "!\{\{(.+?)\}\}!", array $matches = []) : string {
            if (preg_match_all($regex, $template, $matches, PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $key => $match) {
                    if (isset($this->{$match})) {
                        $template = str_replace(sprintf($replace, $match), $this->{$matches[1][$key]}, $template);
                    }
                }
            }
            
            return (string) $template;
        }
    }
}

<?php
namespace Component\Core\Controller\Model {
    use \Component\Validation, \Component\Validator, \Component\Validator\IsString;
    class Browser extends \Component\Core\Controller\Model {   
        public function __construct(array $parameters = []) {
            parent::__construct([
                "server" => new Validation(false, [new Validator\IsArray\Intersect\Key(["HTTP_HOST", "REQUEST_METHOD", "QUERY_STRING", "REQUEST_SCHEME", "REQUEST_URI", "SERVER_PROTOCOL", "DOCUMENT_ROOT", "REMOTE_ADDR"])], Validation::NORMAL),
                "remote" => new Validation(false, [new IsString]),
                "scheme" => new Validation(false, [new IsString\InArray(["http://", "https://"])]),
                "protocol" => new Validation(false, [new IsString]),
                "host" => new Validation(false, [new IsString]),                
                "method" => new Validation(false, [new IsString\InArray(["get", "post", "head", "put", "delete", "connect"])]),                
                "routing" => new Validation(false, [new IsString]),
                "display" => new Validation(new \Component\Core\Parameters, [new Validator\IsObject\Of("\Component\Core\Parameters")])
            ] + $parameters);
        }              
        
        public function setup() {
            if (isset($this->server)) {
                $this->remote = $this->server["REMOTE_ADDR"];
                $this->scheme = sprintf("%s://", $this->server["REQUEST_SCHEME"]);
                $this->protocol = $this->server["SERVER_PROTOCOL"];
                $this->host = $this->server["HTTP_HOST"];
                $this->method = strtolower($this->server["REQUEST_METHOD"]);
                $this->arguments = array_filter(explode(DIRECTORY_SEPARATOR, str_replace("?" . $this->server["QUERY_STRING"], false, ltrim($this->server["REQUEST_URI"], DIRECTORY_SEPARATOR))));
                $this->routing = $this->host . $this->server["REQUEST_URI"];                
            }
        }
          
        final private function initialize(string $template = NULL, string $replace = "{{%s}}", string $regex = "!\{\{(.+?)\}\}!", array $matches = [], array $arguments = []) : string {
            if (preg_match_all($regex, $template, $matches, PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $match) {
                    if (method_exists($this, ($method = parse_url($match, \PHP_URL_PATH))) && is_callable([$this, $method])) {
                        parse_str(parse_url(html_entity_decode($match), \PHP_URL_QUERY), $arguments);
                        $template = str_replace(sprintf($replace, $match), (string) $this->initialize(call_user_func_array([$this, $method], array_values($arguments))), $template);                            
                    }
                }
            }            
            
            return (string) $template;
        }
        
        final public function display(string $template, int $instances = 2, string $replace = "{{%s}}") : string {
            foreach ($this->display->restore($this->display->diff()) as $parameter => $value) {
                $template = implode($value, explode(sprintf($replace, $parameter), $template, $instances));
            }
            
            return (string) $this->initialize($template);
        }        
    }
}


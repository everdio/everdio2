<?php
namespace Component\Core\Controller\Model {
    use \Component\Validation, \Component\Validator, \Component\Validator\IsString;
    class Browser extends \Component\Core\Controller\Model {   
        public function __construct(array $server, array $request, \Component\Parser $parser) {
            parent::__construct([
                "server" => new Validation($server, [new Validator\IsArray\Intersect\Key(["HTTP_HOST", "REQUEST_METHOD", "QUERY_STRING", "REQUEST_SCHEME", "REQUEST_URI", "SERVER_PROTOCOL", "DOCUMENT_ROOT", "REMOTE_ADDR"])], Validation::NORMAL),
                "remote" => new Validation(false, [new IsString]),
                "scheme" => new Validation(false, [new IsString\InArray(["http://", "https://"])]),
                "protocol" => new Validation(false, [new IsString]),
                "host" => new Validation(false, [new IsString]),                
                "method" => new Validation(false, [new IsString\InArray(["get", "post", "head", "put", "delete", "connect"])]),                
                "routing" => new Validation(false, [new IsString])
            ], $parser);

            $this->remote = $this->server["REMOTE_ADDR"];
            $this->scheme = sprintf("%s://", $this->server["REQUEST_SCHEME"]);
            $this->protocol = $this->server["SERVER_PROTOCOL"];
            $this->host = $this->server["HTTP_HOST"];
            $this->method = strtolower($this->server["REQUEST_METHOD"]);
            $this->arguments = array_filter(explode(DIRECTORY_SEPARATOR, str_replace("?" . $this->server["QUERY_STRING"], false, ltrim($this->server["REQUEST_URI"], DIRECTORY_SEPARATOR))));
            $this->routing = $this->host . $this->server["REQUEST_URI"];
            $this->request->store($request);
        }
        
        final public function isReturned() : bool {
            return (bool) (isset($this->server["HTTP_REFERER"]) && (parse_url($this->server["HTTP_REFERER"], PHP_URL_HOST) === $this->host));
        } 
   
        final public function display(string $template, array $values, string $replace = "{{%s}}", string $regex = "!\{\{(.+?)\}\}!", array $matches = []) : string {
            foreach ($values as $property => $value) {
                $template = implode($value, explode(sprintf($replace, $property), $template, 2));
            }
            
            if (preg_match_all($regex, $template, $matches, PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $match) {
                    $parameter = new \Component\Validation\Parameter($match);      
                    $parameter->isValid();
                    if (isset($parameter->{IsString\IsPath::TYPE})) {
                        $template = str_replace(sprintf($replace, $match), $this->display($this->dispatch($parameter->execute()), $values), $template);
                    }
                }
            }
            return (string) $template;
        }         
    }
}


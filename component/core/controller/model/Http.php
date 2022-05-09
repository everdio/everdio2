<?php
namespace Component\Core\Controller\Model {
    use \Component\Validation, \Component\Validator, \Component\Validator\IsString;
    class Http extends \Component\Core\Controller\Model {   
        public function __construct(array $_parameters = []) {
            parent::__construct([
                "server" => new Validation(false, [new Validator\IsArray\Intersect\Key(["HTTP_HOST", "REQUEST_METHOD", "QUERY_STRING", "REQUEST_SCHEME", "REQUEST_URI", "SERVER_PROTOCOL", "DOCUMENT_ROOT", "REMOTE_ADDR"])], Validation::NORMAL),
                "remote" => new Validation(false, [new IsString]),
                "scheme" => new Validation(false, [new IsString\InArray(["http://", "https://"])]),
                "protocol" => new Validation(false, [new IsString]),
                "host" => new Validation(false, [new IsString]),  
                "referer" => new Validation(false, [new IsString\IsUrl]),  
                "method" => new Validation(false, [new IsString\InArray(["get", "post", "head", "put", "delete", "connect"])]),                
                "routing" => new Validation(false, [new IsString])
            ] + $_parameters);
        }          
        
        public function dispatch(string $path) {
            $output = (string) parent::dispatch($path);
            
            if (\file_exists(($file = \sprintf("%s/%s.html", $this->path, $path)))) {
                $output .= $this->caller(\file_get_contents($file));
            }
            
            return (string) $output;
        }
        
        public function setup() : void {
            if (isset($this->server["HTTP_REFERER"])) {
                $this->referer = $this->server["HTTP_REFERER"];
            }

            $this->remote = $this->server["REMOTE_ADDR"];
            $this->scheme = \sprintf("%s://", $this->server["REQUEST_SCHEME"]);
            $this->protocol = $this->server["SERVER_PROTOCOL"];
            $this->host = $this->server["HTTP_HOST"];
            $this->method = \strtolower($this->server["REQUEST_METHOD"]);
            $this->arguments = \array_filter(\explode(\DIRECTORY_SEPARATOR, \str_replace("?" . $this->server["QUERY_STRING"], false, \ltrim($this->server["REQUEST_URI"], \DIRECTORY_SEPARATOR))));
            $this->routing = $this->host . $this->server["REQUEST_URI"]; 
        }  
    }
}


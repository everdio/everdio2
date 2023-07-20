<?php
namespace Component\Core\Controller\Model {
    use \Component\Validation, \Component\Validator, \Component\Validator\IsString;
    class Http extends \Component\Core\Controller\Model {   
        public function __construct(array $_parameters = []) {
            parent::__construct([
                "server" => new Validation(false, [new Validator\IsArray\Intersect\Key(["HTTP_HOST", "REQUEST_METHOD", "QUERY_STRING", "REQUEST_SCHEME", "REQUEST_URI", "SERVER_PROTOCOL", "DOCUMENT_ROOT", "REMOTE_ADDR"])], Validation::NORMAL),
                "scheme" => new Validation(false, [new IsString\InArray(["http://", "https://"])]),
                "referer" => new Validation(false, [new IsString\IsUrl]),  
                "host" => new Validation(false, [new IsString]),  
                "remote" => new Validation(false, [new IsString]),  
                "method" => new Validation(false, [new IsString\InArray(["get", "post", "head", "put", "delete", "connect"])]),                
            ] + $_parameters);
        }          
        
        public function dispatch(string $path) : string {
            $output = (string) parent::dispatch($path);
            
            if (\file_exists($this->path . \DIRECTORY_SEPARATOR . $path . ".html")) {
                //$output .= $this->getCallbacks(\file_get_contents($this->path . \DIRECTORY_SEPARATOR . $path . ".html"));
                $output .= \file_get_contents($this->path . \DIRECTORY_SEPARATOR . $path . ".html");
            }
            
            return (string) (isset($this->request->{$this->debug}) ? $output : \preg_replace(["~\Q/*\E[\s\S]+?\Q*/\E~m", "~(?:http|ftp)s?://(*SKIP)(*FAIL)|//.+~m", "~^\s+|\R\s*~m"], false, $output));
        }
        
        public function setup() : void {
            if (isset($this->server["HTTP_REFERER"])) {
                $this->referer = $this->server["HTTP_REFERER"];
            }
            
            $this->scheme = $this->server["REQUEST_SCHEME"] . "://";
            $this->host = $this->server["HTTP_HOST"];
            $this->remote = $this->server["REMOTE_ADDR"];
            $this->method = \strtolower($this->server["REQUEST_METHOD"]);
            $this->arguments = \implode(\DIRECTORY_SEPARATOR, \array_filter(\explode(\DIRECTORY_SEPARATOR, \str_replace("?" . $this->server["QUERY_STRING"], false, \ltrim($this->server["REQUEST_URI"], \DIRECTORY_SEPARATOR)))));
            $this->remove("server");
        }  
    }
}


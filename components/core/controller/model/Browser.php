<?php
namespace Components\Core\Controller\Model {
    use \Components\Validation, \Components\Validator;
    class Browser extends \Components\Core\Controller\Model {   
        public function __construct(array $server, array $request, \Components\Parser $parser) {
            parent::__construct([
                "server" => new Validation($server, [new Validator\IsArray\Intersect\Key(["HTTP_HOST", "REQUEST_METHOD", "QUERY_STRING", "REQUEST_SCHEME", "REQUEST_URI", "SERVER_PROTOCOL", "DOCUMENT_ROOT", "REMOTE_ADDR"])], Validation::NORMAL),
                "remote" => new Validation(false, [new Validator\IsString]),
                "scheme" => new Validation(false, [new Validator\IsString\InArray(["http://", "https://"])]),
                "protocol" => new Validation(false, [new Validator\IsString]),
                "host" => new Validation(false, [new Validator\IsString]),
                "method" => new Validation(false, [new Validator\IsString\InArray(["get", "post", "head", "put", "delete", "connect"])]),                
                "routing" => new Validation(false, [new Validator\IsString\IsUrl])
            ], $parser);

            $this->remote = $this->server["REMOTE_ADDR"];
            $this->scheme = sprintf("%s://", $this->server["REQUEST_SCHEME"]);
            $this->protocol = $this->server["SERVER_PROTOCOL"];
            $this->host = $this->server["HTTP_HOST"];
            $this->method = strtolower($this->server["REQUEST_METHOD"]);
            $this->arguments = array_filter(explode(DIRECTORY_SEPARATOR, str_replace("?" . $this->server["QUERY_STRING"], false, ltrim($this->server["REQUEST_URI"], DIRECTORY_SEPARATOR))));
            $this->routing = $this->scheme . $this->host . $this->server["REQUEST_URI"];
            $this->request->store($request);
        }
        
        final public function isReturned() : bool {
            return (bool) (isset($this->server["HTTP_REFERER"]) && (parse_url($this->server["HTTP_REFERER"], PHP_URL_HOST) === $this->host));
        }            
        
        public function display(string $path) : string {
            return (string) $this->execute($path);
        }
    }
}


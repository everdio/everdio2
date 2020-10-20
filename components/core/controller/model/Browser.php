<?php
namespace Components\Core\Controller\Model {
    use \Components\Validation, \Components\Validator;
    class Browser extends \Components\Core\Controller\Model {   
        public function __construct(array $server, array $request, \Components\Parser $parser) {
            parent::__construct([
                "server" => new Validation($server, array(new Validator\IsArray\Intersect\Key(array("HTTP_HOST", "REQUEST_METHOD", "QUERY_STRING", "REQUEST_SCHEME", "REQUEST_URI", "SERVER_PROTOCOL", "DOCUMENT_ROOT", "REMOTE_ADDR"))), Validation::NORMAL),
                "remote" => new Validation(false, array(new Validator\IsString)),
                "scheme" => new Validation(false, array(new Validator\IsString\InArray(array("http://", "https://")))),
                "protocol" => new Validation(false, array(new Validator\IsString)),
                "host" => new Validation(false, array(new Validator\IsString)),
                "method" => new Validation(false, array(new Validator\IsString\InArray(array("get", "post", "head", "put", "delete", "connect")))),                
                "default" => new Validation("index.html", array(new Validator\IsString))
            ], $parser);

            $this->remote = $this->server["REMOTE_ADDR"];
            $this->scheme = sprintf("%s://", $this->server["REQUEST_SCHEME"]);
            $this->protocol = $this->server["SERVER_PROTOCOL"];
            $this->host = $this->server["HTTP_HOST"];
            $this->method = strtolower($this->server["REQUEST_METHOD"]);
            $this->arguments = array_filter(explode(DIRECTORY_SEPARATOR, str_replace("?" . $this->server["QUERY_STRING"], false, ltrim($this->server["REQUEST_URI"], DIRECTORY_SEPARATOR))));
            $this->input->store($request);
            $this->remove("server");
        }
        
        public function display(string $path) : string {
            return (string) $this->execute($path);
        }
    }
}


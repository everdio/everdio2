<?php
namespace Components\Core\Controller\Model {
    use \Components\Validation;
    use \Components\Validator;
    class Browser extends \Components\Core\Controller\Model {   
        public function __construct(array $server, array $request, \Components\Parser $parser) {
            parent::__construct($parser);
            $this->add("server", new Validation($server, array(new Validator\IsArray\Intersect\Key(array("HTTP_HOST", "REQUEST_METHOD", "QUERY_STRING", "REQUEST_SCHEME", "REQUEST_URI", "SERVER_PROTOCOL", "DOCUMENT_ROOT", "REMOTE_ADDR"))), Validation::NORMAL));
            $this->add("remote", new Validation($this->server["REMOTE_ADDR"], array(new Validator\IsString)));
            $this->add("scheme", new Validation(sprintf("%s://", $this->server["REQUEST_SCHEME"]), array(new Validator\IsString\InArray(array("http://", "https://")))));
            $this->add("protocol", new Validation($this->server["SERVER_PROTOCOL"], array(new Validator\IsString)));
            $this->add("host", new Validation($this->server["HTTP_HOST"], array(new Validator\IsString)));
            $this->add("method", new Validation(strtolower($this->server["REQUEST_METHOD"]), array(new Validator\IsString\InArray(array("get", "post", "head", "put", "delete", "connect")))));
            $this->add("default", new Validation("index.html", array(new Validator\IsString)));
            
            $this->arguments = array_filter(explode(DIRECTORY_SEPARATOR, str_replace("?" . $this->server["QUERY_STRING"], false, ltrim($this->server["REQUEST_URI"], DIRECTORY_SEPARATOR))));
            $this->input->store($request);
            
            $this->remove("server");
        }
        
        public function display(string $path) : string {
            return (string) $this->execute($path);              ;
        }
    }
}


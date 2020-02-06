<?php
namespace Components\Core\Controller\Model {
    use \Components\Validation;
    use \Components\Validator;
    class Browser extends \Components\Core\Controller\Model {   
        public function __construct(array $server, array $request, \Components\Parser $parser) {
            parent::__construct($parser);
            $this->add("server", new Validation($server, array(new Validator\IsArray\Intersect\Key(array("HTTP_HOST", "HTTP_USER_AGENT", "SERVER_NAME", "REQUEST_METHOD", "QUERY_STRING", "REQUEST_SCHEME", "REQUEST_URI", "REMOTE_ADDR", "SERVER_PROTOCOL", "DOCUMENT_ROOT"))), Validation::NORMAL));
            $this->add("name", new Validation($this->server["SERVER_NAME"], array(new Validator\IsString)));
            $this->add("scheme", new Validation(sprintf("%s://", $this->server["REQUEST_SCHEME"]), array(new Validator\IsString\InArray(array("http://", "https://")))));
            $this->add("protocol", new Validation($this->server["SERVER_PROTOCOL"], array(new Validator\IsString)));
            $this->add("host", new Validation($this->server["HTTP_HOST"], array(new Validator\IsString)));
            $this->add("method", new Validation(strtolower($this->server["REQUEST_METHOD"]), array(new Validator\IsString\InArray(array("get", "post", "head", "put", "delete", "connect")))));
            $this->add("client", new Validation($this->server["REMOTE_ADDR"], array(new Validator\IsString)));
            $this->add("agent", new Validation($this->server["HTTP_USER_AGENT"], array(new Validator\IsString)));
            $this->add("document", new Validation(dirname($this->server["DOCUMENT_ROOT"]) . DIRECTORY_SEPARATOR . basename($this->server["DOCUMENT_ROOT"]), array(new Validator\IsString\IsPath)));
            $this->add("default", new Validation("index.html", array(new Validator\IsString)));
            $this->arguments = array_filter(explode(DIRECTORY_SEPARATOR, str_replace("?" . $this->server["QUERY_STRING"], false, ltrim($this->server["REQUEST_URI"], DIRECTORY_SEPARATOR))));
            $this->request = $request;
            
            foreach ($request as $parameter => $value) {
                $parameter = new \Components\Core\Parameter($parameter);
                $parameter->sample = $value;
                $parameter->mandatory = true;
                $parameter->length = strlen($value);
                $parameter->default = $value;
                $this->input->add($parameter->parameter, $parameter->getValidation());
            }            
        }
        
        final protected function isReturned(string $host) : bool {
            return (bool) (isset($this->server["HTTP_REFERER"]) && (parse_url($this->server["HTTP_REFERER"], PHP_URL_HOST) === $host));
        }        

        final protected function isMethod(string $method) : bool {
            return (bool) ($this->method === strtolower($method));
        }
    }
}


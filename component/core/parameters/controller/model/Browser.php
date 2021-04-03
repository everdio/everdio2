<?php
namespace Component\Core\Parameters\Controller\Model {
    class Browser extends \Component\Core\Parameters\Controller\Model {   
        public function __construct(array $server, array $request, \Component\Parser $parser) {
            parent::__construct($parser);
            $this->server = $server;
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


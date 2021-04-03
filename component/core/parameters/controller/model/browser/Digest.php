<?php
namespace Component\Core\Parameters\Controller\Model\Browser {
    abstract class Digest extends \Component\Core\Parameters\Controller\Model\Browser {   
        protected $digest = [];
        public function __construct(array $server, array $request, \Component\Parser $parser, array $matches = []) {
            parent::__construct($server, $request, $parser);

            if (isset($this->server)) {
                preg_match_all('@(\w+)=(?:(?:\'([^\']+)\'|"([^"]+)")|([^\s,]+))@', $this->server["PHP_AUTH_DIGEST"], $matches, PREG_SET_ORDER);
                foreach ($matches as $match)  {      
                    $this->digest[$match[1]] = ($match[2] ? $match[2] : ($match[3] ? $match[3] : $match[4]));            
                }        
            }
        }
        
        final public function response($password) : string {
            return (string) md5(sprintf("%s:%s:%s:%s:%s:%s", $password, $this->digest["nonce"], $this->digest["nc"], $this->digest["cnonce"], $this->digest["qop"], md5(sprintf("%s:%s", strtoupper($this->method), $this->digest["uri"]))));            
        }

        final public function unAuthorized(string $realm) {
            header("HTTP/1.1 401 Unauthorized");            
            header(sprintf("WWW-Authenticate: Digest realm=\"%s\", qop=\"auth\", nonce=\"%s\", opaque=\"%s\"", $realm, md5(uniqid()), md5(uniqid())));
            header("Content-Type: text/html");
        }      
    }
}


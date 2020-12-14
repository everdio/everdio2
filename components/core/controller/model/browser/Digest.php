<?php
namespace Components\Core\Controller\Model\Browser {
    use \Components\Validation, \Components\Validator;
    abstract class Digest extends \Components\Core\Controller\Model\Browser {   
        protected $digest = [];
        public function __construct(array $server, array $request, \Components\Parser $parser, array $matches = []) {
            parent::__construct($server, $request, $parser);
            $this->add("auth", new Validation($server, [new Validator\IsArray\Intersect\Key(["PHP_AUTH_DIGEST"])]));
            if (isset($this->auth)) {
                preg_match_all('@(\w+)=(?:(?:\'([^\']+)\'|"([^"]+)")|([^\s,]+))@', $this->auth["PHP_AUTH_DIGEST"], $matches, PREG_SET_ORDER);
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


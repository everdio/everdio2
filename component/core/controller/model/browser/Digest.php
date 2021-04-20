<?php
namespace Component\Core\Controller\Model\Browser {
    use \Component\Validation, \Component\Validator;
    abstract class Digest extends \Component\Core\Controller\Model\Browser {   
        public function __construct(array $parameters = []) {
            parent::__construct([
                "auth" => new Validation(false, [new Validator\IsArray\Intersect\Key(["PHP_AUTH_DIGEST"])]),
                "digest" => new Validation(false, [new Validator\IsArray])
            ] + $parameters);
        }
        
        final public function prepare(array $matches = [], array $digest = []) {            
            if (isset($this->auth)) {
                preg_match_all('@(\w+)=(?:(?:\'([^\']+)\'|"([^"]+)")|([^\s,]+))@', $this->auth["PHP_AUTH_DIGEST"], $matches, PREG_SET_ORDER);

                foreach ($matches as $match)  {      
                    $digest[$match[1]] = ($match[2] ? $match[2] : ($match[3] ? $match[3] : $match[4]));            
                } 
                
                $this->digest = $digest;
            }                
            parent::prepare();            
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


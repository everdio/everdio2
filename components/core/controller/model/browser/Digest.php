<?php
namespace Components\Core\Controller\Model\Browser {
    use \Components\Validation;
    use \Components\Validator;
    
    abstract class Digest extends \Components\Core\Controller\Model\Browser {   
        public function __construct(array $server, array $request, \Components\Parser $parser, array $matches = []) {
            parent::__construct($server, $request, $parser);
            $this->add("auth", new Validation($server, array(new Validator\IsArray\Intersect\Key(array("PHP_AUTH_DIGEST")))));
            $this->add("digest", new Validation(false, array(new Validator\IsArray\Intersect\Key(array("nonce", "nc", "cnonce", "qop", "username", "uri", "response")))));
            
            if (isset($this->auth)) {
                preg_match_all('@(\w+)=(?:(?:\'([^\']+)\'|"([^"]+)")|([^\s,]+))@', $this->auth["PHP_AUTH_DIGEST"], $matches, PREG_SET_ORDER);
                foreach ($matches as $match)  {                         
                    $this->digest = array($match[1] => ($match[2] ? $match[2] : ($match[3] ? $match[3] : $match[4])));            
                }        
            }
        }
        
        public function response($password) {
            return (string) md5($password . ":" . $this->digest["nonce"] . ":" . $this->digest["nc"] . ":" . $this->digest["cnonce"] . ":" . $this->digest["qop"] . ":" . md5(strtoupper($this->method) . ":" . $this->digest["uri"]));
        }
        
        public function authorize($username, $password) : bool {               
            return (bool) ($this->digest["username"] === $username && $this->digest["response"] === $this->response($password));
        }          
        
        public function unAuthorized(string $realm) {
            header("HTTP/1.0 401 Unauthorized");            
            header(sprintf("WWW-Authenticate: Digest realm=\"%s\", qop=\"auth\", nonce=\"%s\", opaque=\"%s\"", $realm, uniqid(), md5($realm)));
            header("Content-Type: text/html");
        }      
    }
}


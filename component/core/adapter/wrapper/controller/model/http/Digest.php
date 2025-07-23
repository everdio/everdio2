<?php

namespace Component\Core\Adapter\Wrapper\Controller\Model\Http {

    use \Component\Validation,
        \Component\Validator;

    abstract class Digest extends \Component\Core\Adapter\Wrapper\Controller\Model\Http {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "authorization" => new Validation(false, [new Validator\IsArray\Intersect\Key(["PHP_AUTH_DIGEST"])])
                    ] + $_parameters);
        }

        final public function getDigest(array $digest = [], array $matches = []): array {
            if (isset($this->authorization)) {
                \preg_match_all('@(\w+)=(?:(?:\'([^\']+)\'|"([^"]+)")|([^\s,]+))@', $this->authorization["PHP_AUTH_DIGEST"], $matches, \PREG_SET_ORDER);

                foreach ($matches as $match) {
                    $digest[$match[1]] = ($match[2] ? (string) $match[2] : ($match[3] ? (string) $match[3] : (string) $match[4]));
                }
            }

            return (array) $digest;
        }

        final public function getAuthorization($password, array $digest): string {
            return (string) \md5(sprintf("%s:%s:%s:%s:%s:%s", $password, $digest["nonce"], $digest["nc"], $digest["cnonce"], $digest["qop"], \md5(\sprintf("%s:%s", \strtoupper($this->method), $digest["uri"]))));
        }

        final public function unAuthorized(string $realm) {
            \header("HTTP/1.1 401 Unauthorized");
            \header(\sprintf("WWW-Authenticate: Digest realm=\"%s\", qop=\"auth\", nonce=\"%s\", opaque=\"%s\"", $realm, \md5(\uniqid()), \md5(\uniqid())));
            \header("Content-Type: text/html");
        }
    }

}


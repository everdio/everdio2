<?php

namespace Component\Core\Controller\Model {

    use \Component\Validation,
        \Component\Validator\IsArray,
        \Component\Validator\IsString;

    abstract class Http extends \Component\Core\Controller\Model {

        use \Component\Core\Controller\Model\Auto;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "server" => new Validation(false, [new IsArray\Intersect\Key(["HTTP_HOST", "REQUEST_METHOD", "QUERY_STRING", "REQUEST_SCHEME", "REQUEST_URI", "REMOTE_ADDR", "REQUEST_TIME_FLOAT"])], Validation::NORMAL),
                "scheme" => new Validation(false, [new IsString\InArray(["http://", "https://"])]),
                "referer" => new Validation(false, [new IsString\IsUrl]),
                "host" => new Validation(false, [new IsString]),
                "remote" => new Validation(false, [new IsString]),
                "method" => new Validation(false, [new IsString\InArray(["get", "post", "head", "put", "delete", "connect"])]),
                    ] + $_parameters);
        }

        /*
         * checks if html file exists based on path
         */

        final public function hasHtml(string $path): bool {
            return (bool) \is_file($this->path . \DIRECTORY_SEPARATOR . $path . ".html");
        }

        /*
         * if html file exists, get contents
         */

        final public function getHtml(string $path) {
            if ($this->hasHtml($path)) {
                return (string) \file_get_contents($this->path . \DIRECTORY_SEPARATOR . $path . ".html");
            }
        }

        /*
         * dispatching a html (template) file if exists and adding to parent dispatch (controller)
         * if debug is set (true) output is not compressed
         */

        public function dispatch(string $path): string {
            $output = (string) parent::dispatch($path) . $this->getHtml($path);

            if (isset($this->request->{$this->debug})) {
                return (string) $output;
            }

            return (string) \str_replace(["</source>"], "", \preg_replace(["~\Q/*\E[\s\S]+?\Q*/\E~m", "~(?:http|ftp)s?://(*SKIP)(*FAIL)|//.+~m", "~^\s+|\R\s*~m"], false, $output));
        }

        /*
         * setting up controller reserved parameters to initiate start!
         */

        final public function setup(): void {
            if (isset($this->server["HTTP_REFERER"])) {
                $this->referer = $this->server["HTTP_REFERER"];
            }

            $this->time = (int) $this->server["REQUEST_TIME_FLOAT"];
            $this->scheme = $this->server["REQUEST_SCHEME"] . "://";
            $this->host = $this->server["HTTP_HOST"];
            $this->remote = $this->server["REMOTE_ADDR"];
            $this->method = \strtolower($this->server["REQUEST_METHOD"]);
            $this->arguments = \DIRECTORY_SEPARATOR . \implode(\DIRECTORY_SEPARATOR, \array_filter(\explode(\DIRECTORY_SEPARATOR, \str_replace("?" . $this->server["QUERY_STRING"], false, \ltrim($this->server["REQUEST_URI"], \DIRECTORY_SEPARATOR)))));
        }
    }

}


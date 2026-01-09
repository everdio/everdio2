<?php

namespace Component\Core\Adapter\Wrapper\Controller\Model {

    use \Component\Validation,
        \Component\Validator;

    abstract class Cli extends \Component\Core\Adapter\Wrapper\Controller\Model {
        public function __construct(array $_parameters = []) {
            parent::__construct([
                "server" => new Validation(false, [new Validator\IsArray\Intersect\Key(["argv", "argc", "REQUEST_TIME_FLOAT"])])
                    ] + $_parameters);
        }

        final public function setup(string $options = "--", array $request = [], array $routing = []): void {
            if (isset($this->server) && $this->server["argc"] > 1) {
                foreach (\array_slice($this->server["argv"], 1) as $parameters) {
                    if (\strpos($parameters, $options) !== false) {
                        $routing[] = \str_replace($options, "", $parameters);
                    } else {
                        \parse_str($parameters, $request);
                        $this->request->store(\array_merge_recursive($request, $this->request->restore()));
                    }
                }
           
                $this->time = (int) $this->server["REQUEST_TIME_FLOAT"];
                $this->routing = \implode(\DIRECTORY_SEPARATOR, $routing);
                
                $this->remove("server");                
           } else {
                throw new \RuntimeException("ARGUMENTS_REQUIRED");
           }
        }
    }

}


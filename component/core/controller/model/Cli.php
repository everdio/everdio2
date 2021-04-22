<?php
namespace Component\Core\Controller\Model {
    use \Component\Validation, \Component\Validator;
    class Cli extends \Component\Core\Controller\Model {
        public function __construct(array $parameters = []) {
            parent::__construct([
                "server" => new Validation(false, [new Validator\IsArray\Intersect\Key(["argv", "argc"])])
            ] + $parameters);
        }
        
        final public function setup() {
            if (isset($this->server) && $this->server["argc"] >= 2) {
                foreach (array_slice($this->server["argv"], 2) as $parameters) {
                    if (strpos($parameters, "--") !== false) {
                        $this->arguments = [str_replace("--", false, $parameters)];
                    } else {
                        parse_str($parameters, $request);    
                        $this->request->store($request);
                    }
                }
            }            
        }
        
        public function execute(string $path, array $parameters = []) {
            if ($this->server["argc"] >= 2) {
                return (string) parent::execute($path . DIRECTORY_SEPARATOR . $this->server["argv"][1], $parameters);
            }
            
            throw new \RuntimeException("nothing to execute");
        }
    }
}


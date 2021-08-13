<?php
namespace Component\Core\Controller\Model {
    use \Component\Validation, \Component\Validator;
    class Cli extends \Component\Core\Controller\Model {
        public function __construct(array $_parameters = []) {
            parent::__construct([
                "server" => new Validation(false, [new Validator\IsArray\Intersect\Key(["argv", "argc"])]),
                "route" => new Validation(false, [new Validator\IsString\IsPath])
            ] + $_parameters);
        }
        
        final public function setup(array $request = []) : void {
            if (isset($this->server) && $this->server["argc"] >= 2) {
                $this->route = $this->server["argv"][1];                
                foreach (\array_slice($this->server["argv"], 2) as $parameters) {
                    if (\strpos($parameters, "--") !== false) {
                        $this->arguments = [\str_replace("--", false, $parameters)];
                    } else {
                        \parse_str($parameters, $request);    
                        $this->request->store($request);
                    }                    
                }
            } elseif (!isset($this->server)) {
                throw new \RuntimeException("you suppose to use the command line");
            }            
        }
    }
}


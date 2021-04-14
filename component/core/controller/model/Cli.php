<?php
namespace Component\Core\Controller\Model {
    use \Component\Validation, \Component\Validator;
    final class Cli extends \Component\Core\Controller\Model {
        public function __construct(array $server, \Component\Parser $parser) {
            parent::__construct([
                "server" => new Validation($server, [new Validator\IsArray\Intersect\Key(["argv", "argc"])])
            ], $parser);
            
            if ($this->server["argc"] >= 2) {
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
        
        final public function execute($path) : string {
            if ($this->server["argc"] >= 2) {
                return (string) parent::execute($path . DIRECTORY_SEPARATOR . $this->server["argv"][1]);
            }
            
            throw new \RuntimeException("nothing to execute");
        }
    }
}


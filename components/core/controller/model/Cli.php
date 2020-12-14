<?php
namespace Components\Core\Controller\Model {
    use \Components\Validation, \Components\Validator;
    final class Cli extends \Components\Core\Controller\Model {
        public function __construct(array $server, \Components\Parser $parser, array $request = NULL) {
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
        
        public function display(string $path) {
            return (string) $this->execute($path . DIRECTORY_SEPARATOR . $this->server["argv"][1]);
        }
    }
}


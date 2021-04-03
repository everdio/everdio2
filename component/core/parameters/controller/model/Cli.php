<?php
namespace Component\Core\Parameters\Controller\Model {
    final class Cli extends \Component\Core\Parameters\Controller\Model {
        public function __construct(array $server, \Component\Parser $parser, array $request = NULL) {
            parent::__construct($parser);
            
            $this->server = $server;
            
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
        
        public function execute(string $path) {
            return (string) parent::execute($path . DIRECTORY_SEPARATOR . $this->server["argv"][1]);
        }
    }
}


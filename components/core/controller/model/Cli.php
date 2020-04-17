<?php
namespace Components\Core\Controller\Model {
    use \Components\Validation;
    use \Components\Validator;
    final class Cli extends \Components\Core\Controller\Model {
        public function __construct(array $server, \Components\Parser $parser, array $request = NULL) {
            parent::__construct($parser);
            $this->add("server", new Validation($server, [new Validator\IsArray\Intersect\Key(["argv", "argc"])]));
            $this->add("execute", new Validation(false, [new Validator\IsString\IsPath]));
            if ($this->server["argc"] >= 2) {
                $this->execute = $this->server["argv"][1];
                foreach (array_slice($this->server["argv"], 2) as $parameters) {
                    if (strpos($parameters, "--") !== false) {
                        $this->arguments = [str_replace("--", false, $parameters)];
                    } else {
                        parse_str($parameters, $request);    
                        $this->input->store($request);
                        $this->request = $request;                        
                    }
                }
            }
            
            $this->remove("server");
        }
        
        public function display(string $path) {
            return (string) $this->execute($path . DIRECTORY_SEPARATOR . $this->execute);
        }
    }
}


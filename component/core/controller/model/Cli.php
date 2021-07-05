<?php
namespace Component\Core\Controller\Model {
    use \Component\Validation, \Component\Validator;
    class Cli extends \Component\Core\Controller\Model {
        public function __construct(array $_parameters = []) {
            parent::__construct(_parameters: [
                "server" => new Validation(false, [new Validator\IsArray\Intersect\Key(["argv", "argc"])])
            ] + $_parameters);
        }
        
        final public function setup(array $request = []) : void {
            if (isset($this->server) && $this->server["argc"] >= 2) {
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
        
        final public function echo(string $content, string $break = PHP_EOL) {
            fwrite(STDOUT, $content . $break);
        }
        
        final public function run(string $path, array $parameters = [], string $require = "php") {
            if ($this->server["argc"] >= 2) {
                return (string) $this->execute($path . \DIRECTORY_SEPARATOR . $this->server["argv"][1], $parameters, $require);
            }
            
            throw new \InvalidArgumentException("nothing to execute");
        }
    }
}


<?php
namespace Component\Core\Controller\Model {
    use \Component\Validation, \Component\Validator;
    class Cli extends \Component\Core\Controller\Model {
        public function __construct(array $_parameters = []) {
            parent::__construct([
                "server" => new Validation(false, [new Validator\IsArray\Intersect\Key(["argv", "argc"])]),
                "execute" => new Validation(false, [new Validator\IsString\IsPath]),
                "styles" => new Validation([
                    "bright" => 1, 
                    "italic" => 3, 
                    "underline" => 4, 
                    "blinking" => 5, 
                    "strikethrough" => 9, 
                    "black" => 30, 
                    "red" => 31, 
                    "green" => 32, 
                    "yellow" => 33, 
                    "blue" => 34, 
                    "magenta" => 35, 
                    "cyan" => 36, 
                    "white" => 37, 
                    "blackbg" => 40, 
                    "redbg" => 41, 
                    "greenbg" => 42, 
                    "yellowbg" => 44, 
                    "bluebg" => 44, 
                    "magentabg" => 45, 
                    "cyanbg" => 46, 
                    "lightgreybg" => 47], [new Validator\IsArray])
            ] + $_parameters);            
        }
        
        final public function echo(string $text, array $formats = ["white", "blackbg"], int $breaks = 1) {
            \fwrite(\STDERR, (\sprintf("\033[%sm%s\033[0m%s", \implode(";", \array_flip(\array_intersect(\array_flip($this->styles), $formats))), $text, \str_repeat(\PHP_EOL, $breaks))));
        }
        
        final public function setup(array $request = [], array $arguments = []) : void {
            if ($this->server["argc"] >= 2) {
                $this->execute = $this->server["argv"][1];                
                foreach (\array_slice($this->server["argv"], 2) as $parameters) {
                    if (\strpos($parameters, "--") !== false) {
                        $arguments[] = \str_replace("--", false, $parameters);
                    } else {
                        \parse_str($parameters, $request);
                        $this->request->store(\array_merge_recursive($request, $this->request->restore()));
                        
                    }                    
                }
                
                $this->arguments = \implode(\DIRECTORY_SEPARATOR, $arguments);
            }    
        }
    }
}


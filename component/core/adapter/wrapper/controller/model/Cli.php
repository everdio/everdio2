<?php

namespace Component\Core\Adapter\Wrapper\Controller\Model {

    use \Component\Validation,
        \Component\Validator,
        \Component\Caller;

    class Cli extends \Component\Core\Adapter\Wrapper\Controller\Model {
        use Auto;

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "server" => new Validation(false, [new Validator\IsArray\Intersect\Key(["argv", "argc", "REQUEST_TIME_FLOAT"])])
                    ] + $_parameters);
        }
        
        final protected function addAdapter(): object {
            return (object) new Caller\Ssh2($this->ip);
        }

        final public function break(int $breaks = 1): void {
            $this->echo(\str_repeat(\PHP_EOL, $breaks));
        }

        final public function style(int|string $content, array $styles): string {
            return (string) \sprintf("\e[%sm%s\e[0m", \implode(";", \array_flip(\array_intersect(\array_flip([
                        "bold" => 1,
                        "italic" => 3,
                        "underline" => 4,
                        "blinking" => 5,
                        "strikethrough" => 9,
                        "white" => 37,
                        "black" => 30,
                        "lightgray" => 37,
                        "darkgray" => 90,
                        "red" => 31,
                        "lightred" => 91,
                        "green" => 32,
                        "lightgreen" => 92,
                        "yellow" => 33,
                        "lightyellow" => 93,
                        "blue" => 34,
                        "lightblue" => 94,
                        "magenta" => 35,
                        "lightmagenta" => 95,
                        "cyan" => 36,
                        "lightcyan" => 96,
                        "blackbg" => 40,
                        "redbg" => 41,
                        "greenbg" => 42,
                        "yellowbg" => 44,
                        "bluebg" => 44,
                        "magentabg" => 45,
                        "cyanbg" => 46,
                        "lightgreybg" => 47]), $styles))), $content);
        }

        final public function echo(int|string $content): void {
            (new Caller\File\Fopen("php://stdout"))->puts($content);
        }

        final public function setup(string $options = "--", array $request = [], array $arguments = []): void {
            if (isset($this->server) && $this->server["argc"] > 1) {
                foreach (\array_slice($this->server["argv"], 1) as $parameters) {
                    if (\strpos($parameters, $options) !== false) {
                        $arguments[] = \str_replace($options, "", $parameters);
                    } else {
                        \parse_str($parameters, $request);
                        $this->request->store(\array_merge_recursive($request, $this->request->restore()));
                    }
                }
           
                $this->time = (int) $this->server["REQUEST_TIME_FLOAT"];
                $this->arguments = \implode(\DIRECTORY_SEPARATOR, $arguments);
                $this->hostname = \gethostname();
                $this->ip = \gethostbyname($this->hostname);
           } else {
                throw new \RuntimeException("Arguments required, nothing to execute");
            }
        }
    }

}


<?php

namespace Component\Core\Controller\Model {

    use \Component\Validation,
        \Component\Validator;

    class Cli extends \Component\Core\Controller\Model {

        public function __construct(array $_parameters = []) {
            parent::__construct([
                "server" => new Validation(false, [new Validator\IsArray\Intersect\Key(["argv", "argc", "PWD", "SCRIPT_FILENAME"])]),
                "execute" => new Validation(false, [new Validator\IsString\IsPath])
                    ] + $_parameters);
        }

        final public function input(): string {
            return (string) \trim(\fgets(\STDIN));
        }

        final public function break(int $breaks = 1): void {
            $this->echo(\str_repeat(\PHP_EOL, $breaks));
        }

        final public function echo(string $content, array $styles = ["white", "blackbg"]): void {
            (new \Component\Caller\File\Fopen("php://stdout"))->puts(\sprintf("\e[%sm%s\e[0m", \implode(";", \array_flip(\array_intersect(\array_flip([
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
                "lightgreybg" => 47]), $styles))), $content));
        }

        final public function setup(array $request = [], array $arguments = []): void {
            $this->self = $this->server["PWD"] . \trim($this->server["SCRIPT_FILENAME"], ".");
            if ($this->server["argc"] >= 2) {
                $this->execute = $this->server["argv"][1];
                foreach (\array_slice($this->server["argv"], 2) as $parameters) {
                    if (\strpos($parameters, "--") !== false) {
                        $arguments[] = \str_replace("--", "", $parameters);
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


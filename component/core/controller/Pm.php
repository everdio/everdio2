<?php
namespace Component\Core\Controller {
    final class Pm extends \Component\Core\Controller {   
        public function execute(string $path) : void {
            if (isset($this->oid) && $this->oid === "linux") {
                \exec(\sprintf("%s %s %s > /dev/null &", $path, (isset($this->arguments) ? \sprintf("--%s", \implode(" --", \explode(\DIRECTORY_SEPARATOR, $this->arguments))) : false), \http_build_query($this->request->restore(), false, " ")));
            }
        }
    }
}
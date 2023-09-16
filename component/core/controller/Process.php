<?php

namespace Component\Core\Controller {

    final class Process extends \Component\Core\Controller {

        public function execute(string $path, array $parameters = []) {
            return (\sprintf("%s %s %s > /dev/null &", $this->self, $path, \urldecode(\http_build_query(\array_merge($this->restore($this->diff()), $parameters), false, " "))));
        }
    }

}
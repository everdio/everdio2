<?php
namespace Components\Core\Controller\Model {
    final class Cmd extends \Components\Core\Controller\Model {
        final public function display($route) {
            exec(sprintf("%s/everdio %s %s %s > /dev/null &", $this->root, $route, (isset($this->arguments) ? implode(DIRECTORY_SEPARATOR, $this->arguments) : false), (isset($this->request) ? $this->sanitize(http_build_query($this->request, false, " ")) : false)));
        }
    }
}
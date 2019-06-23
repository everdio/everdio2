<?php
namespace Modules\Controller {
    final class Cmd extends \Components\Core\Controller\Model {
        public function execute($route) {
            exec(sprintf("%s/everdio execute=%s %s %s > /dev/null &", $this->root, $route, (isset($this->arguments) ? implode(DIRECTORY_SEPARATOR, $this->arguments) : false), (isset($this->request) ? $this->sanitize(http_build_query($this->request, false, " ")) : false)));
        }
    }
}
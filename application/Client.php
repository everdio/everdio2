<?php

namespace Application {

    abstract class Client extends \Component\Core\Adapter\Wrapper\Controller\Model\Http {
        final protected function addAdapter(): object {
            return (object) new \Component\Caller\Ssh2($this->ip);
        }
    }

}

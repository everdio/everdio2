<?php
namespace Modules {
    class BaseX extends \Components\Core\Adapter {
        public function prepare() {
            $this->setopt_array([CURLOPT_URL => sprintf("%s?query=%s", $this->host, urlencode($this->query)), CURLOPT_USERPWD => sprintf("%s:%s", $this->username, $this->password)]);
        }
        
        public function setup($key) : \DOMDOcument {
            try {
                $dom = new \DOMDocument;
                $dom->loadXML(sprintf("<%s>%s</%s>", strtolower($key), $this->execute(), strtolower($key)));
                new \Components\Core\Adapter\Instance($key, $dom);
                return (object) $dom;
            } catch (\Components\Core\Caller\Event $event) {
                throw new Event($event->getMessage());
            }
        }
    }
}


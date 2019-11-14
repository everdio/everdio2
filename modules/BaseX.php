<?php
namespace Modules {
    use \Components\Validation;
    use \Components\Validator;    
    class BaseX extends \Components\Core\Adapter {
        public function __construct($key) {
            parent::__construct($key);
            $this->add("root", new Validation(false, array(new Validator\IsString)));
        }
        
        public function prepare() {
            $this->setopt_array([CURLOPT_URL => sprintf("%s?query=%s", $this->host, urlencode($this->query)), CURLOPT_USERPWD => sprintf("%s:%s", $this->username, $this->password)]);
        }
  
        public function initialize($key) : \Components\Core\Adapter {
            try {
                $dom = new \DOMDocument;
                $dom->recover = true;
                $dom->loadXML((isset($this->root) ? sprintf("<%s>%s</%s>", strtolower($this->root), $this->execute(), strtolower($this->root)) : $this->execute()));
                $instance = new \Components\Core\Adapter\Instance($key, $dom);
                return (object) $instance;
            } catch (\Components\Core\Caller\Event $event) {
                throw new Event($event->getMessage());
            }
        }
    }
}


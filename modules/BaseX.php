<?php
namespace Modules {
    use \Modules\Node;    
    use \Components\Validation;
    use \Components\Validator;    
    abstract class BaseX extends \Components\Core\Adapter {
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
                $dom->loadXML((isset($this->root) ? sprintf("<%s>%s</%s>", strtolower($this->root), $this->execute(), strtolower($this->root)) : $this->execute()));
                $instance = new \Components\Core\Adapter\Instance($key, $dom);
                return (object) $instance;
            } catch (\Components\Core\Caller\Event $event) {
                throw new Event($event->getMessage());
            }
        }      

        public function query(string $xpath, $wrap = "%s", array $filters = []) {
            foreach ($this->filters as $filter => $node) {
                if (class_exists($node) && isset($this->{$filter})) {
                    $filters[] = new Node\Filter($node::construct()->path, $this->{$filter});
                }
            }
            $path = new Node\Path($xpath, $filters, $wrap);
            $this->query = $path->execute();
            $this->prepare();        
        }
    }
}


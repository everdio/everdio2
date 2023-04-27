<?php   
namespace Modules\Autocallbacks {
    use \Component\Validation, \Component\Validator;
    class Memcached extends \Modules\Autocallbacks {
        final public function autoCallbacks(string $parameter, bool $cache = false) : void {
            if (isset($this->{$parameter}) && $cache) {
                $memcached = new \Memcached($this->memcached->id);
                $memcached->setOption(\Memcached::OPT_COMPRESSION, true);
                if (empty($memcached->getServerList())) {
                    $memcached->addServer($this->memcached->host, $this->memcached->port);
                }
                
                $key = \md5($this->arguments . "?" . $this->request->querystring() . "#" . $parameter);
                
                if (isset($this->request->{$this->debug}) || (!isset($this->request->{$this->debug}) && !$memcached->get($key) && $memcached->getResultCode() !== 0)) {
                    parent::autoCallbacks($parameter);
                    //$memcached->set($key, \serialize($this->controller->restore($this->controller->diff($this->{$parameter}->diff()))), $this->memcached->ttl);
                }     
                
                //$this->controller->store(\unserialize($memcached->get($key)));
                
            } else {
                parent::autoCallbacks($parameter);
            }            
        }
    }
}

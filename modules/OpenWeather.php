<?php
namespace Modules {
    abstract class OpenWeather extends \Components\Core\Adapter {
        public function initialize(string $key) : \Components\Core\Adapter {              
            $this->setopt_array([CURLOPT_URL => sprintf("%s?%s", $this->url, http_build_query($this->restore(array("appid", "lang", "units", "lat", "lon", "mode")))), CURLOPT_USERPWD]);           
            
            try {
                $instance = new \Components\Core\Adapter\Instance($key, new \DOMDocument);
                $instance->loadXML(trim($this->execute()));
                return (object) $instance;
            } catch (\Components\Core\Caller\Event $event) {
                throw new Event($event->getMessage());
            }
        }             
    }
}


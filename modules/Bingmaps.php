<?php
//http://dev.virtualearth.net/REST/v1/Locations/{point}?&key={BingMapsKey}  
namespace Modules {
    class Bingmaps extends \Components\Core\Adapter {
        public function initialize() {            
            if (!\array_key_exists($this->unique($this->diff()), self::$_adapters)) {
                $curl = new \Component\Caller\Curl;
                $curl->setopt_array([
                    \CURLOPT_FOLLOWLOCATION => true, 
                    \CURLOPT_RETURNTRANSFER => true]);
                self::$_adapters[$hash] = $curl;
            }
            
            return (object) self::$_adapters[$hash];
        }
        
        final public function execute(string $query) : string {
            $this->setopt(\CURLOPT_URL, \sprintf("%s/%s?%s", $this->url, $this->point, \urlencode($query)));
            return (string) $this->execute();
        }        

    }
}
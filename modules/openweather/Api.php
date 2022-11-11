<?php
namespace Modules\OpenWeather {
    trait Api {
        use \Modules\Node, \Modules\OpenWeather;
        public function query(string $query) : \DOMNodeList {
            return (object) $this->xpath($this->request::construct(["lat" => $this->lat, "lon" => $this->lon, "lang" => $this->lang])->fetch($query))->query($query);
        }
    }
}
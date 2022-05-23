<?php
namespace Modules\OpenWeather {
    trait Api {
        use \Modules\Node, \Modules\OpenWeather;
        public function query(string $query) : \DOMNodeList {
            $request = new $this->request;
            $request->lat = $this->lat;
            $request->lon = $this->lon;
            $request->lang = $this->lang;
            return (object) $this>xpath($request->fetch($query))->query($query);
        }
    }
}
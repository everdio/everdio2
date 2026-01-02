<?php

namespace Modules {

    trait Featherdown {

        final protected function addAdapter(): object {
            $curl = new \Component\Caller\Curl;
            $curl->setopt_array([
                \CURLOPT_FOLLOWLOCATION => true,
                \CURLOPT_RETURNTRANSFER => true]);

            return (object) $curl;
        }

        final protected function getResponse(string $url) {
            try {
                $this->setopt(\CURLOPT_URL, $url);
                return (string) $this->execute();
            } catch (\ErrorException $ex) {
                throw new \LogicException("FEATHERDOWN " . $this->host . ": " . $ex->getMessage());
            }
        }

        public function getLocations() {
            return (string) $this->getResponse(\sprintf("%s/api/v2/locations?%s", $this->url, $this->querystring(["username", "password", "locale"])));
        }

        public function getAvailabilities() {
            return (string) $this->getResponse(\sprintf("%s/api/v2/availabilities?%s", $this->url, $this->querystring(["username", "password", "locale", "location"])));
        }
    }

}

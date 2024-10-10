<?php

namespace Component {

    trait Finder {

        final public function finder(string $path, array $arguments = [], string $seperator = \DIRECTORY_SEPARATOR) {
            foreach (\explode($seperator, $path) as $part) {
                return (isset($this->{$part}) ? ($this->{$part} instanceof self ? $this->{$part}->finder(\implode($seperator, \array_diff(\explode($seperator, $path), [$part])), $arguments) : $this->{$part}) : $this->callback($part, $arguments));
            }
        }

        final public function callback(string $url, array $arguments = []) {
            $function = \parse_url($url, \PHP_URL_HOST);

            if (($query = \parse_url($url, \PHP_URL_QUERY))) {
                \parse_str($query, $arguments);
            }
            
            $arguments = $this->hydrate(\array_values($arguments));
            
            //if (($method = \parse_url($url, \PHP_URL_SCHEME))) {                                        
            if (($method = \strstr($url, ":", true))) {
                try {
                    return ($function ? \call_user_func($function, \call_user_func_array([$this, $method], $arguments)) : \call_user_func_array([$this, $method], $arguments));
                } catch (\TypeError $ex) {
                    throw new \BadMethodCallException($ex->getMessage());
                } catch (\ErrorException $ex) {
                    throw new \InvalidArgumentException($ex->getMessage());
                }
            } elseif ($function) {
                try {
                    return \call_user_func_array($function, $arguments);
                } catch (\TypeError $ex) {
                    throw new \BadFunctionCallException($ex->getMessage());
                }
            }
        }
    }

}
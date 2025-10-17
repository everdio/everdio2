<?php

namespace Component {

    trait Finder {

        final public function finder(string $path, array $arguments = [], string $seperator = \DIRECTORY_SEPARATOR): mixed {
            foreach (\explode($seperator, $path) as $part) {
                return (isset($this->{$part}) ? ($this->{$part} instanceof self ? $this->{$part}->finder(\implode($seperator, \array_diff(\explode($seperator, $path), [$part])), $arguments) : (\is_array(($array = $this->{$part})) && \array_key_exists(($end = \implode($seperator, \array_diff(\explode($seperator, $path), [$part]))), $array) ? $array[$end] : $array)) : $this->callback($part, $arguments));
            }
        }

        final public function callback(string $callback, array $arguments = []) {
            $function = \parse_url($callback, \PHP_URL_HOST);
            
            if (($query = \parse_url($callback, \PHP_URL_QUERY))) {
                \parse_str($query, $arguments);
            }
            
            $arguments = $this->hydrate(\array_values($arguments));
                        
            //if (($method = \parse_url($callback, \PHP_URL_SCHEME))) {                                        
            if (($method = \strstr($callback, ":", true))) {
                try {
                    return ($function && \is_callable($function) ? \call_user_func($function, \call_user_func_array([$this, $method], $arguments)) : \call_user_func_array([$this, $method], $arguments));
                } catch (\TypeError $ex) {
                    throw new \BadMethodCallException(\sprintf("%s::%s: %s", \get_class($this), $method, $ex->getMessage()), 0, $ex);
                } catch (\ErrorException $ex) {
                    throw new \InvalidArgumentException(\sprintf("%s::%s %s", \get_class($this), $method, $ex->getMessage()), 0, $ex);
                }
            } elseif ($function) {
                if (\is_callable($function)) {
                    try {
                        return \call_user_func_array($function, $arguments);
                    } catch (\TypeError $ex) {
                        throw new \BadFunctionCallException(\sprintf("%s (%s): %s", \get_class($this), $function, $ex->getMessage()), 0, $ex);
                    } catch (\ErrorException $ex) {
                        throw new \InvalidArgumentException(\sprintf("%s::%s %s", \get_class($this), $function, $ex->getMessage()), 0, $ex);
                    }
                } else {
                    switch ($function) {
                        case "eval":
                            return eval(\implode(false, $arguments));
                            break;
                        case "echo":
                            return eval(\implode(false, $arguments));
                            break;
                    }
                }
            }
        }
    }

}
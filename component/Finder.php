<?php

namespace Component {

    trait Finder {

        final public function finder(string $path, array $arguments = [], string $seperator = \DIRECTORY_SEPARATOR): mixed {
            foreach (\explode($seperator, $path) as $part) {
                return (isset($this->{$part}) ? ($this->{$part} instanceof self ? $this->{$part}->finder(\implode($seperator, \array_diff(\explode($seperator, $path), [$part])), $arguments) : (\is_array(($value = $this->{$part})) && \array_key_exists(($end = \implode($seperator, \array_diff(\explode($seperator, $path), [$part]))), $value) ? $value[$end] : $this->{$part})) : $this->callback($part, $arguments));
            }
        }
        
        final public function callback(string $url, array $arguments = [], bool $required = false) {
            $function = \parse_url($url, \PHP_URL_HOST);

            if (($query = \parse_url($url, \PHP_URL_QUERY))) {
                \parse_str($query, $arguments);
            }
            
            $arguments = $this->hydrate(\array_values($arguments));
                        
            //if (($method = \parse_url($url, \PHP_URL_SCHEME))) {                                        
            if (($method = \strstr($url, ":", true))) {
                try {
                    return ($function && \is_callable($function) ? \call_user_func($function, \call_user_func_array([$this, $method], $arguments)) : \call_user_func_array([$this, $method], $arguments));
                } catch (\TypeError $ex) {
                    throw new \BadMethodCallException(\sprintf("%s->%s(%s): %s", \get_class($this), $method, $this->dehydrate($arguments), $ex->getMessage()), 0, $ex);
                } catch (\ErrorException $ex) {
                    throw new \InvalidArgumentException(\sprintf("%s->%s(%s) %s", \get_class($this), $method, $this->dehydrate($arguments), $ex->getMessage()), 0, $ex);
                }
            } elseif ($function) {
                if (\is_callable($function)) {
                    try {
                        return \call_user_func_array($function, $arguments);
                    } catch (\TypeError $ex) {
                        throw new \BadFunctionCallException(\sprintf("%s %s(%s): %s", \get_class($this), $function, $this->dehydrate($arguments), $ex->getMessage()), 0, $ex);
                    } catch (\ErrorException $ex) {
                        throw new \InvalidArgumentException(\sprintf("%s->%s(%s) %s", \get_class($this), $function, $this->dehydrate($arguments), $ex->getMessage()), 0, $ex);
                    }
                } elseif ($function === "eval") {
                    //deadly eval(); for artimetchi operators
                    try {
                        return eval(\sprintf("return %s;", \implode(false, $arguments)));
                    } catch (\Error $ex) {
                        throw new \InvalidArgumentException($ex->getMessage());
                    }
                }
            }
        }
    }

}
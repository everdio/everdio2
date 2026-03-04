<?php

namespace Component {

    trait Finder {

        final public function finder(string $path, array $arguments = [], string $seperator = \DIRECTORY_SEPARATOR): mixed {
            foreach (\explode($seperator, $path) as $part) {
                if (isset($this->{$part})) {
                    if (($diff = \implode($seperator, \array_diff(\explode($seperator, $path), [$part])))) {
                        if ($this->{$part} instanceof self) {
                            return $this->{$part}->finder($diff, $arguments);
                        } elseif (\is_array(($value = $this->{$part})) && \array_key_exists($diff, $value)) {
                            return $value[$diff];
                        } elseif (\is_object($this->{$part})) {
                            return $this->finderobj($this->{$part}, $diff, $arguments, $seperator);
                        }
                    }

                    return $this->{$part};
                } else {
                    return $this->callback($part, $arguments);
                }

                //return (isset($this->{$part}) ? ($this->{$part} instanceof self ? $this->{$part}->finder(\implode($seperator, \array_diff(\explode($seperator, $path), [$part])), $arguments) : (\is_array(($value = $this->{$part})) && \array_key_exists(($end = \implode($seperator, \array_diff(\explode($seperator, $path), [$part]))), $value) ? $value[$end] : $this->{$part})) : $this->callback($part, $arguments));
            }
        }

        final public function finderobj(object $object, string $path, array $arguments = [], string $seperator = \DIRECTORY_SEPARATOR): mixed {
            foreach (\explode($seperator, $path) as $part) {
                if (isset($object->{$part})) {
                    if (($diff = \implode($seperator, \array_diff(\explode($seperator, $path), [$part])))) {
                        if (\is_object($object->{$part})) {
                            return $this->finderobj($object->{$part}, $diff, $arguments, $seperator);
                        } elseif (\is_array(($value = $object->{$part})) && \array_key_exists($diff, $value)) {
                            return $value[$property];
                        }
                    }
                    return $object->{$part};
                } else {
                    return $this->callurl($part, $object, $arguments);
                }
            }
        }

        final public function callurl(string $url, object $object, array $arguments = []) {
            $function = \parse_url($url, \PHP_URL_HOST);

            if (($query = \parse_url($url, \PHP_URL_QUERY))) {
                \parse_str($query, $arguments);
            }

            $arguments = $this->hydrate(\array_values($arguments));

            //if (($method = \parse_url($url, \PHP_URL_SCHEME))) {                                        
            if (($method = \strstr($url, ":", true))) {
                return $this->callmethod($method, $object, $arguments, $function);
            } elseif ($function) {
                return $this->callfunction($function, $arguments);
            }
        }

        final public function callback(string $url, array $arguments = []) {
            return $this->callurl($url, $this, $arguments);
        }

        final public function callmethod(string $method, object $object, array $arguments = [], ?string $function = NULL) {
            try {
                return ($function && \is_callable($function) ? \call_user_func($function, \call_user_func_array([$object, $method], $arguments)) : \call_user_func_array([$object, $method], $arguments));
            } catch (\TypeError $ex) {
                throw new \BadMethodCallException(\sprintf("%s->%s(%s): %s", \get_class($object), $method, $this->dehydrate($arguments), $ex->getMessage()), 0, $ex);
            } catch (\ErrorException $ex) {
                throw new \InvalidArgumentException(\sprintf("%s->%s(%s) %s", \get_class($object), $method, $this->dehydrate($arguments), $ex->getMessage()), 0, $ex);
            }
        }

        final public function callfunction(string $function, array $arguments = []) {
            if (\is_callable($function)) {
                try {
                    return \call_user_func_array($function, $arguments);
                } catch (\TypeError $ex) {
                    throw new \BadFunctionCallException(\sprintf("%s(%s): %s", $function, $this->dehydrate($arguments), $ex->getMessage()), 0, $ex);
                } catch (\ErrorException $ex) {
                    throw new \InvalidArgumentException(\sprintf("%s(%s) %s", $function, $this->dehydrate($arguments), $ex->getMessage()), 0, $ex);
                }
            } else {
                try {
                    switch ($function) {
                        case "eval":
                            return eval(\sprintf("return %s;", \implode(false, $arguments)));
                        case "echo":
                            echo \implode(false, $arguments);
                            break;
                        default:
                            return $function(\implode(false, $arguments));
                    }
                } catch (\Error $ex) {
                    throw new \InvalidArgumentException($ex->getMessage());
                }
            }
        }
    }

}
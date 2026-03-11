<?php

namespace Component {

    trait Finder {

        final public function finder(string $path, array $arguments = [], string $seperator = \DIRECTORY_SEPARATOR): mixed {
            foreach (\explode($seperator, $path) as $part) {
                if (isset($this->{$part})) {
                    return $this->found($this->{$part}, $this->finderpath($path, $part), $arguments, $seperator);
                } else {
                    return $this->callback($part, $arguments);
                }
            }
        }

        private function finderpath(string $path, string $part, string $seperator = \DIRECTORY_SEPARATOR): string {
            return (string) \implode($seperator, \array_diff(\explode($seperator, $path), [$part]));
        }

        private function found(mixed $value, string $path, array $arguments = [], string $seperator = \DIRECTORY_SEPARATOR): mixed {
            if ($path) {
                if ($value instanceof self) {
                    return $value->finder($path, $arguments, $seperator);
                } elseif (\is_object($value)) {
                    return $this->foundobj($value, $path, $arguments, $seperator);
                } elseif (\is_array($value)) {
                    return $this->foundarr($value, $path, $arguments, $seperator);
                }
            }

            return $value;
        }

        private function foundobj(object $object, string $path, array $arguments = [], string $seperator = \DIRECTORY_SEPARATOR): mixed {
            if ($path) {
                foreach (\explode($seperator, $path) as $part) {
                    if (isset($object->{$part})) {
                        return $this->found($object->{$part}, $this->finderpath($path, $part), $arguments, $seperator);
                    }

                    return $this->callurl($part, $object, $arguments);
                }
            }

            return $object;
        }

        private function foundarr(array $array, string $path, array $arguments = [], string $seperator = \DIRECTORY_SEPARATOR): mixed {
            if ($path) {
                foreach (\explode($seperator, $path) as $part) {
                    if (\array_key_exists($array, $part)) {
                        return $this->found($array[$part], $this->finderpath($path, $part), $arguments, $seperator);
                    }
                }
            }

            return $array;
        }

        final public function callback(string $url, array $arguments = []) {
            return $this->callurl($url, $this, $arguments);
        }

        final public function callurl(string $url, object $object, array $arguments = []) {
            $function = \parse_url($url, \PHP_URL_HOST);
    
            if (($query = \parse_url($url, \PHP_URL_QUERY))) {
                \parse_str($query, $arguments);
            }

            $arguments = $this->hydrate(\array_values($arguments));
            $method = \strstr($url, ":", true);
            //$method = \parse_url($url, \PHP_URL_SCHEME);
                
            if ($method && $function) {
                return $this->callfunction($function, [$this->callmethod($method, $object, $arguments)]);
            } elseif ($method) {
                return $this->callmethod($method, $object, $arguments);
            } elseif ($function) {
                return $this->callfunction($function, $arguments);
            }
        }

        final public function callmethod(string $method, object $object, array $arguments = []) {
            try {
                return \call_user_func_array([$object, $method], $arguments);
            } catch (\Errror $ex) {
                throw new \BadMethodCallException(\sprintf("%s->%s(%s): %s", \get_class($object), $method, $this->dehydrate($arguments), $ex->getMessage()), 0, $ex);
            } catch (\ErrorException $ex) {
                throw new \InvalidArgumentException(\sprintf("%s->%s(%s) %s", \get_class($object), $method, $this->dehydrate($arguments), $ex->getMessage()), 0, $ex);
            }
        }

        final public function callfunction(string $function, array $arguments = []) {
            if (\is_callable($function)) {
                try {
                    return \call_user_func_array($function, $arguments);
                } catch (\Error $ex) {
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
                        case "empty":
                            return empty(\implode(false, $arguments));
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
<?php

namespace Component {

    trait Dryer {

        public function dehydrate($data, array $array = []): string {
            if (\is_integer($data) || \is_numeric($data)) {
                return (string) $data;
            } elseif (\is_bool($data)) {
                return (string) ($data === true ? "true" : "false");
            } elseif (\is_string($data)) {
                return (string) \sprintf("\"%s\"", \addcslashes($data, "\""));
            } elseif (\is_array($data)) {
                foreach ($data as $key => $value) {
                    $array[] = (\is_integer($key) ? false : $this->dehydrate($key) . " => ") . $this->dehydrate($value);
                }

                return (string) \sprintf("[%s]", \implode(", ", $array));
            } elseif (\is_object($data)) {
                return (string) (\method_exists($data, __FUNCTION__) ? $data->__dry() : \sprintf("new \%s", \get_class($data)));
            } elseif ($data === null) {
                return (string) "false";
            } elseif (\is_resource($data)) {
                return (string) \get_resource_type($data);
            } else {
                throw new \ValueError(\sprintf("Unexpected data type %s", \gettype($data)));
            }
        }

        public function hydrate($data): mixed {
            if (\is_string($data)) {
                if (\is_numeric($data)) {
                    if (\floatval($data) != \intval($data)) {
                        return (float) $data;
                    } else {
                        return (int) $data;
                    }
                }

                return (string) $data;
            } elseif (\is_integer($data)) {
                if (\floatval($data) != \intval($data)) {
                    return (float) $data;
                } else {
                    return (int) $data;
                }
            } elseif (\is_array($data)) {
                foreach ($data as $key => $value) {
                    $data[$key] = $this->hydrate($value);
                }

                return (array) $data;
            } else {
                return $data;
            }
        }

        abstract public function __dry(): string;
    }

}
<?php

namespace Component\Core {

    class Parameters extends \Component\Core {

        /**
         * 
         * @param string $parameter
         * @param type $value
         */
        final public function __set(string $parameter, $value) {
            $validation = new \Component\Validation\Parameter($value, true);
            $this->addParameter($parameter, $validation->getValidation(), true);
        }

        /**
         * 
         * @param array $values
         * @return self
         */
        final public function store(array $values): self {
            foreach ($values as $field => $value) {
                if (\is_array($value)) {
                    if (!isset($this->{$field})) {
                        $this->{$field} = new self;
                        $this->{$field}->store($value);
                    } else {
                        if ($this->{$field} instanceof self) {
                            $this->{$field}->store($value);
                        } else {
                            $this->{$field} = $value;
                        }
                    }
                } else {
                    $this->{$field} = $value;
                }
            }

            return (object) $this;
        }

        /**
         * 
         * @param array $parameters
         * @param array $values
         * @return array
         */
        final public function restore(array $parameters = [], array $values = []): array {
            foreach (parent::restore($this->diff($parameters), $values) as $field => $value) {
                $values[$field] = ($value instanceof self ? $value->restore() : $value);
            }

            return (array) $values;
        }

        /**
         * 
         * @param array $parameters
         * @return string
         */
        final public function arguments(array $parameters = []): string {
            return (string) \http_build_query([$this->restore($parameters)]);
        }

        /**
         * 
         * @param string $seperator
         * @return string
         */
        final public function implode(string $seperator = ", "): string {
            return (string) \implode($seperator, (array) $this->restore());
        }

        /**
         * 
         * @return string
         */
        final public function __dry(): string {
            return (string) \sprintf("new \%s(%s)", (string) $this, $this->dehydrate($this->export($this->diff())));
        }
    }

}
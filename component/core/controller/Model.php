<?php

namespace Component\Core\Controller {

    use \Component\Validation,
        \Component\Validator,
        \Component\Core\Parameters,
        \Component\Caller\File\Fopen;

    abstract class Model extends \Component\Core\Controller {
        /*
         * A required setup function to process the basic server input for the controller
         */

        abstract public function setup(): void;

        /*
         * dispatching the Model if exists
         */

        public function dispatch(string $path): string {
            return (string) parent::dispatch($this->getModel($path, false));
        }

        /*
         * checks if model ini file exists
         */

        final protected function hasModel(string $path): bool {
            return (bool) \is_file($this->path . \DIRECTORY_SEPARATOR . $path . ".ini");
        }

        /*
         * parsing ini contents and set as Parameters container(s)
         */

        final public function getModel(string $path, bool $reset = true): string {
            if ($this->hasModel($path)) {
                try {
                    $ini = new Fopen\Ini($this->path . \DIRECTORY_SEPARATOR . $path, "r");
                    foreach (\array_diff_key($ini->read(), \array_flip($this->reserved)) as $parameter => $value) {
                        if (\is_array($value)) {
                            $this->addParameter($parameter, new Validation(new Parameters, [new Validator\IsObject]), $reset);
                            $this->{$parameter}->store($value);
                        } else {
                            $validation = new Validation\Parameter($this->getCallbacks($value), true);
                            $this->addParameter($parameter, $validation->getValidation($validation->getValidators()));
                        }
                    }
                } catch (\ErrorException $ex) {
                    throw new \LogicException(\sprintf("invalid ini file %s: %s", $this->path . \DIRECTORY_SEPARATOR . $path . ".ini", $ex->getMessage()), 0, $ex);
                }
            }

            return (string) $path;
        }

        /*
         * creating and (re)storing an ini file, directory should exist
         */

        public function save(string $path, array $parameters): void {
            if (\sizeof(($sections = $this->restore(\array_diff($this->inter($parameters), $this->reserved))))) {
                $ini = new Fopen\Ini((new \Component\Path(\dirname($this->path . \DIRECTORY_SEPARATOR . $path)))->getPath() . \DIRECTORY_SEPARATOR . \basename($path), "w");
                foreach ($sections as $section => $parameters) {
                    if ($parameters instanceof Parameters) {
                        $ini->write($parameters->restore(), $section);
                    }
                }
            }
        }

        public function __clone() {
            $controller = parent::__clone();
            $controller->getModel($controller->basename);
            return (object) $controller;
        }
    }

}
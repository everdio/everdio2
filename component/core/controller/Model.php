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
            return (string) parent::dispatch($this->getModel($path));
        }

        /*
         * checks if model ini file exists
         */

        final public function hasModel(string $path): bool {
            return (bool) \is_file($this->path . \DIRECTORY_SEPARATOR . $path . ".ini");
        }

        /*
         * creating and (re)storing an ini file, directory should exist
         */

        final public function saveModel(string $path): void {
            if (\sizeof(($sections = $this->restore($this->diff($this->reserved))))) {
                $ini = new Fopen\Ini((new \Component\Path(\dirname($this->path . \DIRECTORY_SEPARATOR . $path)))->getPath() . \DIRECTORY_SEPARATOR . \basename($path), "w");
                foreach ($sections as $section => $parameters) {
                    if ($parameters instanceof Parameters) {
                        $ini->write($parameters->restore(), $section);
                    }
                }
            }
        }

        /*
         * parsing ini contents and set as Parameters container(s)
         */

        final public function getModel(string $path, bool $reset = false): string {
            if ($this->hasModel($path)) {
                try {
                    $ini = new Fopen\Ini($this->path . \DIRECTORY_SEPARATOR . $path, "r");
                    foreach (\array_diff_key($ini->read(), \array_flip($this->reserved)) as $parameter => $value) {
                        if (\is_array($value)) {
                            $this->addParameter($parameter, new Validation(new Parameters, [new Validator\IsObject]), $reset);
                            $this->{$parameter}->store($value);
                        } else {
                            $validation = new Validation\Parameter($value, true);
                            $this->addParameter($parameter, $validation->getValidation($validation->getValidators()) , $reset);
                        }
                    }
                } catch (\ErrorException $ex) {
                    throw new \LogicException(\sprintf("invalid ini file %s: %s", $this->path . \DIRECTORY_SEPARATOR . $path . ".ini", $ex->getMessage()), 0, $ex);
                }
            }

            return (string) $path;
        }

        final public function deleteModel(string $path): void {
            if ($this->hasModel($path)) {
                \unlink($this->path . \DIRECTORY_SEPARATOR . $path . ".ini");
            }
        }

        final public function resetModel(array $parameters = []) {
            foreach (\array_diff($this->diff($parameters), $this->reserved) as $parameter) {
                $this->remove($parameter);
            }
        }
    }

}
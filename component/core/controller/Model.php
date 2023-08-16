<?php

namespace Component\Core\Controller {

    use \Component\Validation,
        \Component\Validator,
        \Component\Core\Parameters;

    abstract class Model extends \Component\Core\Controller {
        /*
         * A required setup function to process the basic server input for the controller
         */

        abstract public function setup(): void;

        /*
         * A required echo function to output data
         */

        abstract public function echo(string $content): void;

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

        final public function saveModel(string $path) {
            $ini = new \Component\Caller\File\Fopen\Ini(\Component\Path::construct(\dirname($this->path . \DIRECTORY_SEPARATOR . $path))->getPath() . \DIRECTORY_SEPARATOR . \basename($path), "w");
            foreach ($this->restore($this->diff($this->_reserved)) as $key => $parameters) {
                if ($parameters instanceof Parameters) {
                    $ini->store($key, $parameters->restore());
                }
            }
        }

        /*
         * parsing ini contents and set as Parameters container(s)
         */

        final public function getModel(string $path, bool $reset = false): string {
            if ($this->hasModel($path)) {
                foreach (\array_merge_recursive(\parse_ini_file($this->path . \DIRECTORY_SEPARATOR . $path . ".ini", true, \INI_SCANNER_TYPED)) as $parameter => $value) {
                    if (!\in_array($parameter, $this->_reserved)) {
                        if (\is_array($value)) {
                            $this->addParameter($parameter, new Validation(new Parameters, [new Validator\IsObject]), $reset);
                            $this->{$parameter}->store($value);
                        } else {
                            $this->addParameter($parameter, new Validation\Parameter($value), $reset);
                        }
                    }
                }
            }

            return (string) $path;
        }

        final public function resetModel(array $parameters = []) {
            foreach (\array_diff($this->diff($parameters), $this->_reserved) as $parameter) {
                $this->remove($parameter);
            }
        }
    }

}
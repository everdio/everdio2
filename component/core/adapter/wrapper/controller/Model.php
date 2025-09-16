<?php

namespace Component\Core\Adapter\Wrapper\Controller {

    use \Component\Validation,
        \Component\Validator,
        \Component\Core\Parameters;

    abstract class Model extends \Component\Core\Adapter\Wrapper\Controller {
        use Auto;
        
        /*
         * A required setup function to process the basic server input for the controller
         */

        abstract public function setup(): void;

        /*
         * parsing ini contents and set as Parameters container(s)
         */

        final public function getModel(string $path, bool $reset = true): string {
            if (\is_file($this->path . \DIRECTORY_SEPARATOR . $path . ".ini")) {
                try {
                    $parameters = (array) \parse_ini_string($this->replace(\file_get_contents($this->path . \DIRECTORY_SEPARATOR . $path . ".ini"), $this->diff()), true, \INI_SCANNER_TYPED);

                    foreach (\array_diff_key($parameters, \array_flip($this->reserved)) as $parameter => $value) {
                        if (\is_array($value)) {
                            $this->addParameter($parameter, new Validation(new Parameters, [new Validator\IsObject]), $reset);
                            $this->{$parameter}->store($value);
                        } else {
                            $validation = new Validation\Parameter($this->getCallbacks($value), true);
                            $this->addParameter($parameter, $validation->getValidation());
                        }
                    }
                } catch (\ErrorException $ex) {
                    throw new \RuntimeException(\sprintf("INVALID_INI_FILE %s (%s)", $this->path . \DIRECTORY_SEPARATOR . $path . ".ini", $ex->getMessage()), 0, $ex);
                }
            }

            return (string) $path;
        }

        /*
         * dispatching the Model if exists
         */

        public function dispatch(string $path): string|null|int {
            return parent::dispatch($this->getModel($path, false));
        }

        /*
         * creating and (re)storing an ini file, directory should exist
         */

        public function save(string $path, array $parameters): void {
            if (\sizeof(($sections = $this->restore(\array_diff($this->inter($parameters), $this->reserved))))) {
                $ini = new \Component\Caller\File\Fopen\Ini((new \Component\Path(\dirname($this->path . \DIRECTORY_SEPARATOR . $path)))->getPath() . \DIRECTORY_SEPARATOR . \basename($path), "w");
                foreach ($sections as $section => $parameters) {
                    if ($parameters instanceof Parameters) {
                        $ini->write($parameters->restore(), $section);
                    }
                }
            }
        }
    }

}
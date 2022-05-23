<?php
namespace Modules {
    abstract class Image {
        const MIME = self::MIME;
        const TYPE = self::TYPE;
        /*  
         * public resource $image
         */
        protected $image;

        /*
         * protected int width;
         */
        protected $width = 0;

        /*
         * protected int $height
         */
        protected $height = 0;    

        /*
         * protected int $left
         */
        protected $left = 0;

        /*
         * protected int $top
         */
        protected $top = 0;
        
        /*
         * creating from an existing image
         */
        abstract protected function create($file);

        public function import($file) {
            if (\exif_imagetype($file) === $this::TYPE) {
                $this->image = $this->create($file);
            } else {
                throw new \LogicException(\sprintf("invalid image type %s for %s", \exif_imagetype($file), \basename($file)));
            }
        }

        /*
         * resamples image with new dimensions
         */
        private function _resample(int $width, int $height) : bool {
            $image = \imagecreatetruecolor((int) $width, (int) $height);
            \imagecopyresampled($image, $this->image, (int) $this->left, (int) $this->top, 0, 0, (int) $this->width, (int) $this->height, (int) \imagesx($this->image), (int) imagesy($this->image));
            return (bool) $this->image = $image;
        }

        /*
         * crops image to width and height
         */
        public function crop(int $width, int $height, int $top = 0, int $left = 0) : bool {                        
            if (\imagesx($this->image) / \imagesy($this->image) >= $width / $height) {
                $this->width = (int) \imagesx($this->image) / (\imagesy($this->image) / $height);
                $this->height = $height;  
            } else {
                $this->width = $width;
                $this->height = (int) \imagesy($this->image) / (\imagesx($this->image) / $width);
            }

            $this->left = (int) ($left - ($this->width - $width) / 2);        
            $this->top = (int) ($top - ($this->height - $height) / 2);        

            return (bool) $this->_resample($width, $height);
        }    

        /*
         * resizes image by width and height crops if needed
         */
        public function resize(int $width = NULL, int $height = NULL) : bool {        
            $this->height = (int) ($width && !$height ? \imagesy($this->image) * ($width / \imagesx($this->image)) : $height);
            $this->width = (int) (!$width && $height ? \imagesx($this->image) * ($height / \imagesy($this->image)) : $width);                                            
            return (bool) $this->_resample($this->width, $this->height);
        }        
        
        /*
         * rotates the image with an angle (degrees);
         */
        public function rotate(int $angle) {
            $this->image = \imagerotate($this->image, 360 - $angle, 0);
        }

        /*
         * scales image with int
         */
        public function scale(int $scale) : bool {            
            return (bool) $this->resize((($scale / 100) * \imagesx($this->image)), (($scale / 100) * \imagesy($this->image)));
        }    

        /*
         * export and save image
         */
        abstract public function export($image, string $file, $compression = 75) : bool; 

        /*
         * display image
         */
        abstract public function display($compression = 75);        

        /*
         * save image
         */
        public function save(string $file, $compression = 75) : bool {
            return (bool) $this->export($this, $file, $compression);
        }     
        
        public function __destruct() {
            unset ($this->image);
        }
    }
}
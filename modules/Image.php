<?php
namespace Modules {
    abstract class Image {
        /*
         * public resource $image
         */
        protected $image = false;

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
        abstract protected function create($path);
        
        public function input($path) {
            if (exif_imagetype($path) === $this::IMAGE_TYPE) {
                $this->image = $this->create($path);
            } else {
                throw new Event(sprintf("invalid image type %s", $path));
            }
        }

        /*
         * resamples image with new dimensions
         */
        private function resample(int $width, int $height) : bool {
            $image = imagecreatetruecolor((int) $width, (int) $height);
            imagecopyresampled($image, $this->image, (int) $this->left, (int) $this->top, 0, 0, (int) $this->width, (int) $this->height, (int) imagesx($this->image), (int) imagesy($this->image));
            return (bool) $this->image = $image;
        }

        /*
         * crops image to width and height
         */
        public function crop(int $width, int $height, int $top = 0, int $left = 0) : bool {                        
            if (imagesx($this->image) / imagesy($this->image) >= $width / $height) {
                $this->width = imagesx($this->image) / (imagesy($this->image) / $height);
                $this->height = $height;  
            } else {
                $this->width = $width;
                $this->height = imagesy($this->image) / (imagesx($this->image) / $width);
            }

            $this->left = $left - ($this->width - $width) / 2;        
            $this->top = $top - ($this->height - $height) / 2;        

            return (bool) $this->resample($width, $height);
        }    

        /*
         * resizes image by width and height crops if needed
         */
        public function resize(int $width = NULL, int $height = NULL) : bool {        
            $this->height = ($width && !$height ? imagesy($this->image) * ($width / imagesx($this->image)) : $height);
            $this->width = (!$width && $height ? imagesx($this->image) * ($height / imagesy($this->image)) : $width);                                            
            return (bool) $this->resample($this->width, $this->height);
        }        
        
        /*
         * rotates the image with an angle (degrees);
         */
        public function rotate(int $angle) {
            $this->image = imagerotate($this->image, 360 - $angle, 0);
        }

        /*
         * scales image with int
         */
        public function scale(int $scale) : bool {            
            return (bool) $this->resize((($scale / 100) * imagesx($this->image)), (($scale / 100) * imagesy($this->image)));
        }    

        /*
         * export and save image
         */
        abstract public function export(\Modules\Image $image, \SplFileObject $file, $compression = 75) : bool; 

        /*
         * display image
         */
        abstract public function display($compression = false);        

        /*
         * save image
         */
        public function save(\SplFileObject $target, $compression = 75) : bool {
            return (bool) $this->export($this, $target, $compression);
        }      
        
        public function __destruct() {
            if (is_resource($this->image)) {
                imagedestroy($this->image);
            }
        }
    }
}
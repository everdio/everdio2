<?php
namespace Modules\Everdio {
    use \Modules\Everdio\Library\ECms;
    class File extends \Modules\Everdio\Library\ECms\File {
        public function find(array $thatMappers = [], string $query = NULL) {
            parent::find($thatMappers, $query);
            try {
                $file = new \Components\File($this->File, "r");
            } catch (\RuntimeException $ex) {
                $this->delete();
            }
        }
        
        public function update() {
            if (isset($this->FileId)) {
                $file = new \Components\File($this->File, "r");
                $this->Size = $file->getSize();
                $this->Basename = $file->getBasename("." . $file->getExtension());
                $this->Extension = $file->getExtension();        
                $this->Size = $file->getSize();
                parent::save();            
            }
        }
        
        public function delete() {
            foreach (ImageFile::construct(array("FileId" => $this->FileId))->findAll() as $row) {
                $imagefile = new ImageFile($row);
                
                $imagefilegallery = new ECms\ImageFileGallery($row);
                unset($imagefilegallery->Order);
                
                $imagefilegallery->delete();
                $imagefile->delete();
            }
            
            parent::delete();
        }
    }
}
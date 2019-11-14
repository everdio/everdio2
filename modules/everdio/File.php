<?php
namespace Modules\Everdio {
    use \Modules\Everdio\Library\ECms;
    class File extends ECms\File {
        public function save() {
            $file = new \Components\File($this->File, "r");
            $this->Size = $file->getSize();
            $this->Basename = $file->getBasename("." . $file->getExtension());
            $this->Extension = $file->getExtension();        
            $this->Size = $file->getSize();
            parent::save();            
        }
        
        public function delete() {
            $imagefile = new ImageFile;
            $imagefile->FileId = $this->FileId;
            $imagefile->delete();
            
            parent::delete();
        }
    }
}
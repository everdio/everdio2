<?php
namespace Modules\Everdio {
    use \Modules\Everdio\Library\ECms;
    class Routing extends \Modules\Everdio\Library\ECms\Routing {
        public function save() {
            $values = $this->restore($this->mapping);
            
            $this->reset($this->mapping);
            
            $this->LocaleId = $values["LocaleId"];
            $this->EnvironmentId = $values["EnvironmentId"];
            $this->ExecuteId = $values["ExecuteId"];
            $this->DocumentId = $values["DocumentId"];
            $this->find();
            
            $routing = (isset($this->Routing) ? $this->Routing : false);
            
            $this->reset($this->mapping);
            $this->store($values);
            
            $environment = new ECms\Environment;
            $environment->EnvironmentId = $this->EnvironmentId;
            unset ($environment->Status);
            $environment->find();

            $environment_locale = new ECms\EnvironmentLocale;
            $environment_locale->EnvironmentId = $this->EnvironmentId;
            $environment_locale->LocaleId = $this->LocaleId;
            $environment_locale->find();

            if (!isset($this->Routing)) {
                $document = new ECms\Document;
                $document->DocumentId = $this->DocumentId;
                $document->find();
                
                if ($document->ParentId !== 0) {                
                    $routing = new ECms\Routing;
                    $routing->LocaleId = $this->LocaleId;
                    $routing->EnvironmentId = $this->EnvironmentId;
                    $routing->DocumentId = $document->ParentId;
                    unset ($routing->Status);
                    unset ($routing->Display);                
                    $routing->find();
                    if (isset($routing->Routing)) {
                        $this->Routing = $environment_locale->WebPath . basename($routing->Routing, $environment->Extension) . DIRECTORY_SEPARATOR . $this->slug($document->Document) . $environment->Extension;                    
                    } else {
                        $this->Routing = $environment_locale->WebPath . $this->slug($document->Document) . $environment->Extension;
                    }
                } else {
                    $this->Routing = $environment_locale->WebPath . $this->slug($document->Document) . $environment->Extension;
                }
            }
            
            if ($routing && $routing !== $this->Routing) {
                $redirect = new ECms\Redirect;
                $redirect->Redirect = $environment->Scheme . $environment->Host . DIRECTORY_SEPARATOR . $environment_locale->WebPath . $routing;
                $redirect->Routing = $environment->Scheme . $environment->Host . DIRECTORY_SEPARATOR . $environment_locale->WebPath . $this->Routing;
                $redirect->Status = 302;
                $redirect->save();
                $redirect->reset($redirect->mapping);
                $redirect->Redirect = $environment->Scheme . $environment->Host . DIRECTORY_SEPARATOR . $environment_locale->WebPath . $this->Routing;
                $redirect->delete();
            }            
    
            parent::save();
        }
    }
}


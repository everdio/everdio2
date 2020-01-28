<?php
namespace Modules\Everdio {
    use \Modules\Everdio\Library\ECms;
    class Document extends \Modules\Everdio\Library\ECms\Document {
        public function save(array $keywords = []) {
            if (!isset($this->DocumentId)) {
                $document = new $this;
                $document->reset($document->mapping);
                $document->DocumentSlug = $this->slug($this->Document);
                $results = $document->findAll();

                $this->DocumentSlug = (sizeof($results) ? sprintf("%s-%s", $this->slug($this->Document), count($results) + 1) : $this->slug($this->Document));
            } else {
                $this->DocumentSlug = $this->slug($this->Document);
            }
            
            if (empty($this->Description)) {
                $this->Description = $this->substring(implode(". ", $this->phrases($this->Content)), 0, 250, false, "...");
            }         
            
            if (empty($this->Keywords)) {
                $account = new ECms\Account;
                $account->AccountId = $this->AccountId;
                unset($account->Status);
                $account->find();

                $keywords[] = sprintf("%s %s", $account->FirstName, $account->LastName);
                
                if (isset($this->DocumentId)) {
                    foreach (self::construct(array("ParentId" => $this->DocumentId))->findAll() as $row) {
                        $document = new Document($row);
                        if (!empty($document->Content)) {
                            $keywords = array_unique(array_merge($keywords, array_unique($this->words($document->Content, 6, 1))));
                        }
                    }
                }
                
                $this->Keywords = $this->substring(implode(",", array_unique(array_merge($keywords, array_unique($this->words($this->Title, 4, 3) + $this->words($this->Description, 4, 1) + $this->words(strip_tags($this->desanitize($this->Content)), 4, 8))))), 0, 255);
            }            
            
            if (empty($this->Order) && !empty($this->ParentId)) {
                $this->Order = count(ECms\Document::construct(array("EnvironmentId" => $this->EnvironmentId, "ParentId" => $this->ParentId))->findAll()) + 1;
            }         
            
            parent::save();
        }
    }
}

